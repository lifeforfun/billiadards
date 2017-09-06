<?php

namespace api\controllers;

use Yii;
use common\block\ThumbTrait;
use common\block\SaveFileTrait;
use common\models\News;
use common\models\UploadFile;
use yii\db\Exception;
use yii\db\Query;
use yii\web\HttpException;
use yii\web\UploadedFile;

class NewsController extends \api\lib\Controller
{

    use SaveFileTrait;

    /**
     * 信息列表
     * @var integer $page 页数,默认1
     * @var integer $pagesize 每页条数，默认10
     * @var string $tags 按标签搜索，多个标签用竖线(|)隔开
     * @var string $q 搜索标题
     * @return string
     */
    public function actionIndex()
    {
        $page = (int)self::getPost('page', 1);
        $pagesize = (int)self::getPost('pagesize', 10);
        $tags = (string)self::getPost('tags', '');
        $q = (string)self::getPost('q', '');

        $query = (new Query())
            ->select( ['id', 'title', 'dateline', 'cover'] )
            ->from('news')
            ->orderBy(['dateline' => SORT_DESC]);

        $query
            ->where(['status' => 1])
            ->andFilterWhere(['like', 'title', $q]);

        if ($tags!=='') {
            $tags = explode('|', $tags);
            $_tags = [];
            foreach ($tags as $tag) {
                $_tags[] = '|' . $tag . '|';
            }
            $query->andFilterWhere(['or like', 'tag', $_tags]);
        }

        $total = $query->count();

        $list = $query
            ->offset(($page-1) * $pagesize)
            ->limit($pagesize)
            ->all();
        if (empty($list)) {
            $list = array();
        }

        foreach ($list as &$item) {
            if ($item['cover']) {
                $item['cover'] = ThumbTrait::getThumb($item['cover'], 'small');
            }
        }
        unset($item);

        return $this->asJson([
            'status' => true,
            'data' => [
                'total' => $total,
                'list' => $list,
            ]
        ]);
    }

    /**
     * @var integer $id
     * @return array
     */
    public function actionDetail()
    {
        $id = self::getQuery('id');

        $data = array(
            'video' => null,
            'pic' => []
        );
        $model = News::findOne(['id' => $id, 'status' => 1]);
        if (!$model) {
            throw new HttpException(404, '信息不存在或未通过审核');
        }
        if ($model->vid) {
            $video = UploadFile::findOne(['id' => $model->vid]);
            $data['video'] = $video->url;
        }
        if ($model->pids) {
            $pics = (new Query())
                ->select('url')
                ->from('upload_file')
                ->where(['in', 'id', explode(',', $model->pids)])
                ->all();
            foreach ($pics as $pic) {
                $data['pic'][] = [
                    'thumb' => ThumbTrait::getThumb($pic['url'], 'mid'),
                    'url' => $pic['url'],
                ];
            }
        }

        $data = array_merge($data, $model->getAttributes(['id', 'title', 'dateline', 'tag', 'content']));

        $data['relate'] = News::find()
            ->select(['id', 'title', 'cover', 'dateline'])
            ->where('status=1 AND id!=:id', [
                ':id' => $id
            ])
            ->limit(10)
            ->asArray()
            ->all();

        foreach ($data['relate'] as &$item) {
            $item['cover'] = ThumbTrait::getThumb($item['cover'], 'small');
        }
        unset($item);

        $this->registerJsFile('news/detail.js');
        return $this->render('detail', [
            'detail' => $data
        ]);
    }

    /**
     * @var integer $uid 用户id
     * @var string $session 用户登录令牌
     * @var File $video 上传视频
     * @var File[] $pic 上传图片数组
     * @var string $title 标题
     * @var string $content 内容
     */
    public function actionCreate()
    {
        $video = UploadedFile::getInstanceByName('video');
        $pics = UploadedFile::getInstancesByName('pic');
        $title = trim((string)self::getPost('title'));
        $content = trim((string)self::getPost('content'));

        if (!self::checkLogin()) {
            return $this->asJson([
                'status' => false,
                'msg' => '登录失效'
            ]);
        }

        if ($title==='') {
            return $this->asJson([
                'status' => false,
                'msg' => '请填写标题'
            ]);
        }

        if ($content==='') {
            return $this->asJson([
                'status' => false,
                'msg' => '请填写内容'
            ]);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $news = new News([
                'uid' => self::getPost('uid'),
                'title' => $title,
                'status' => 0,
                'dateline' => date('Y-m-d'),
                'pids' => [],
                'content' => $content
            ]);

            if ($video
                && !$video->hasError
                && in_array($video->extension, ['mp4', 'flv', 'avi'])
                && $resp = $this->saveFile($video)) {
                $fvideo = new UploadFile([
                    'filepath' => $resp['path'],
                    'url' => $resp['url'],
                    'type' => 'video',
                ]);
                if (!$fvideo->validate()) {
                    throw new Exception('视频校验失败');
                }
                $fvideo->save();
                $news->vid = $fvideo->id;
            }

            foreach ($pics as $pic) {
                if ($pic
                    && !$pic->hasError
                    && in_array($pic->extension, ['jpg', 'png', 'gif'])
                    && $resp = $this->saveFile($pic)) {
                    ThumbTrait::setThumb($resp['path']);
                    $fpic = new UploadFile([
                        'filepath' => $resp['path'],
                        'url' => $resp['url'],
                        'type' => 'pic',
                    ]);
                    if (!$fpic->validate()) {
                        throw new Exception('图片校验失败');
                    }
                    $fpic->save();
                    $news->cover = $news->cover ? : $fpic->url;
                    $news->pids = array_merge($news->pids, [$fpic->id]);
                }
            }

            $news->pids = implode(',', $news->pids);
            $news->save();

        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->asJson([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

        $transaction->commit();

        return $this->asJson([
            'status' => true
        ]);
    }
}
