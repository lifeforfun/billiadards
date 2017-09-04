<?php
/**
 * Created by PhpStorm.
 * User: ff
 * Date: 5/29/17
 * Time: 5:44 PM
 */
namespace backend\controllers;

use Yii;
use backend\lib\Controller;
use common\models\News;
use yii\base\Exception;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $this->registerJsFile('news/index.js');
        return $this->render('index');
    }

    public function actionList()
    {
        $where = '';
        $bind = [];

        if (self::getQuery('title')!=='') {
            $where .= 'title like :title';
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

        $page = (int)self::getQuery('page', 1);
        $pagesize = (int)self::getQuery('pagesize', 10);

        $query = (new Query())
            ->from('news')
            ->where($where, $bind);

        $total = $query->count();
        $list = $query
            ->select(['id', 'title', 'dateline', 'cover'])
            ->orderBy(['dateline' => SORT_DESC])
            ->offset(($page-1)*$pagesize)
            ->limit($pagesize)
            ->all();

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
            $this->registerJsFile('news/edit.js');
            return $this->render('edit', [
                'model' => $model
            ]);
        }

        try {
            // 保存POST
            $model->setAttributes(self::getPost());
            $model->setTag($model->tag);
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
            if (isset($_FILES['cover']) && isset($_FILES['cover']['tmp_name'])) {
                $model->cover = UploadedFile::getInstanceByName('cover');
                if (!$model->cover || !$model->upload()) {
                    return $this->asJson([
                        'status' => false,
                        'msg' => '封面图片保存失败'
                    ]);
                }
            }

            if (!$model->cover) {
                return $this->asJson([
                    'status' => false,
                    'msg' => '请上传封面图'
                ]);
            }

            $model->save();
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
}