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
        if (!Yii::$app->request->isPost) {
            if ($id) {
                $model = News::findOne(['id' => $id]);
            } else {
                $model = new News();
            }
            $this->registerJsFile('news/edit.js');
            return $this->render('edit', [
                'model' => $model
            ]);
        }

    }
}