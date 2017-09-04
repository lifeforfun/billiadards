<?php

namespace api\controllers;

use common\block\ThumbTrait;
use common\models\News;
use common\models\UploadFile;
use yii\db\Query;

class NewsController extends \api\lib\Controller
{
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
            ->filterWhere([
                ['like', 'title', $q]
            ]);

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

        return self::asJson([
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
        $id = self::getPost('id');

        $data = array(
            'video' => null,
            'pic' => []
        );
        $model = News::findOne(['id' => $id, 'status' => 1]);
        if (!$model) {
            return self::asJson([
                'status' => false,
                'msg' => '信息不存在或未通过审核'
            ]);
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

        return self::asJson([
            'status' => true,
            'data' => $data,
        ]);
    }

    /**
     *
     */
    public function actionCreate()
    {

    }
}
