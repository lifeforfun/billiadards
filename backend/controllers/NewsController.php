<?php
/**
 * Created by PhpStorm.
 * User: ff
 * Date: 5/29/17
 * Time: 5:44 PM
 */
namespace backend\controllers;

use common\block\SaveFileTrait;
use common\block\ThumbTrait;
use common\models\UploadFile;
use Yii;
use backend\lib\Controller;
use common\models\News;
use yii\db\Exception;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class NewsController extends Controller
{

    use SaveFileTrait;

    public function actionIndex()
    {
        $this->registerJsFile('news/index.js');
        return $this->render('index');
    }

    public function actionList()
    {
        $where = '1';
        $bind = [];

        if (self::getQuery('title')!=='') {
            $where .= ' AND title like :title';
            $bind[':title'] = '%'.self::getQuery('title').'%';
        }

        if (self::getQuery('start')!=='') {
            $where .= ' AND dateline>=:start';
            $bind[':start'] = self::getQuery('start');
        }

        if (self::getQuery('end')!=='') {
            $where .= ' AND dateline<=:end';
            $bind[':end'] = self::getQuery('end');
        }

        if (self::getQuery('status')!=='') {
            $where .= ' AND status=:status';
            $bind[':status'] = self::getQuery('status');
        }

        $page = (int)self::getQuery('page', 1);
        $pagesize = (int)self::getQuery('pagesize', 10);

        $query = (new Query())
            ->from('news')
            ->where($where, $bind);

        $total = $query->count();
        $list = $query
            ->select(['id', 'title', 'dateline', 'cover', 'status'])
            ->orderBy(['dateline' => SORT_DESC])
            ->offset(($page-1)*$pagesize)
            ->limit($pagesize)
            ->all();

        foreach ($list as &$item) {
            if ($item['cover']) {
                $item['cover'] = ThumbTrait::getThumb($item['cover'], 'small');
            }
        }

        return $this->asJson(['status' => true, 'data' => [
            'list' => $list,
            'total' => $total
        ]]);
    }

    public function actionEdit()
    {
        $id = self::getQuery('id');
        if ($id && !(new Query())->from('news')->where(['id' => $id])->exists()) {
            throw new BadRequestHttpException('信息不存在或已被删除');
        }

        if ($id) {
            $model = News::findOne(['id' => $id]);
        } else {
            $model = new News();
        }

        if (!Yii::$app->request->isPost) {

            if ($model->vid) {
                $video = UploadFile::findOne(['id' => $model->vid]);
            }

            if ($model->pids) {
                $pics = UploadFile::findAll(explode(',', $model->pids));
            }

            $this->registerJsFile('news/edit.js');
            return $this->render('edit', [
                'model' => $model,
                'video' => isset($video) ? $video : null,
                'pics' => isset($pics) ? $pics : [],
            ]);
        }

        try {
            // 保存POST
            $model->setAttributes(self::getPost());
            if (!$model->title) {
                return $this->asJson([
                    'status' => false,
                    'msg' => '请填写标题'
                ]);
            }
            if (!$model->dateline) {
                $model->dateline = date('Y-m-d');
            }
            if (!$model->content) {
                return $this->asJson([
                    'status' => false,
                    'msg' => '请填写内容'
                ]);
            }

            $video = UploadedFile::getInstanceByName('video');
            if ($video && !$video->hasError && in_array($video->extension, ['mp4', 'flv', 'avi'])) {
                $oldVideo = $model->vid ? UploadFile::findOne($model->vid) : null;
                $resp = self::saveFile($video);
                if ($resp) {
                    if ($oldVideo) {
                        $oldVideo->delete();
                        unlink($oldVideo->filepath);
                    }
                    $video = new UploadFile([
                        'url' => $resp['url'],
                        'filepath' => $resp['path'],
                        'type' => 'video'
                    ]);
                    if (!$video->save()) {
                        $errors = $video->getErrors();
                        throw new Exception(current(current($errors)));
                    }
                    $model->vid = $video->id;
                }
            }

            if (isset($_FILES['pic']) && is_array($_FILES['pic']['tmp_name'])) {
                $pids = [];
                if ($model->pids) {
                    $pics = UploadFile::findAll(explode(',', $model->pids));
                }else {
                    $pics = [];
                }
                foreach ($_FILES['pic']['name'] as $i => $v) {
                    $pic = new UploadedFile([
                        'name' => $_FILES['pic']['name'][$i],
                        'tempName' => $_FILES['pic']['tmp_name'][$i],
                        'type' => $_FILES['pic']['type'][$i],
                        'size' => $_FILES['pic']['size'][$i],
                        'error' => $_FILES['pic']['error'][$i],
                    ]);
                    if ($pic && !$pic->hasError && in_array($pic->extension, ['jpg', 'png', 'gif'])) {
                        $resp = self::saveFile($pic);
                        if (!$resp) {
                            continue;
                        }
                        $pic = new UploadFile([
                            'filepath' => $resp['path'],
                            'url' => $resp['url'],
                            'type' => 'pic'
                        ]);
                        if (!$pic->save()) {
                            continue;
                        }
                        ThumbTrait::setThumb($resp['path']);
                        if (isset($pics[$i])) {
                            $pics[$i]->delete();
                            if (is_file($pics[$i]->filepath)) {
                                unlink($pics[$i]->filepath);
                            }
                            ThumbTrait::deleteThumb($pics[$i]->filepath);
                        }
                        $pics[$i] = $pic;
                    }
                }
                $coverSet = false;
                foreach ($pics as $pic) {
                    $pids[] = $pic->id;
                    if (!$coverSet) {
                        $coverSet = true;
                        $model->cover = $pic->url;
                    }
                }
                $model->pids = implode(',', $pids);
            }

            if (!$model->save()) {
                $errors = $model->getErrors();
                throw new Exception(current(current($errors)));
            }
        } catch (\Exception $e) {
            return $this->asJson([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

        return $this->asJson([
            'status' => true
        ]);
    }

    public function actionAudit()
    {
        $id = self::getQuery('id');
        $news = News::findOne(['id' => $id]);
        if (!$news) {
            return $this->asJson([
                'status' => false,
                'msg' => '信息不存在'
            ]);
        }
        $news->status = 1;
        if (!$news->save()) {
            return $this->asJson([
                'status' => false,
                'msg' => '信息状态修改失败'
            ]);
        }

        return $this->asJson([
            'status' => true
        ]);
    }

    public function actionDelete()
    {

    }
}