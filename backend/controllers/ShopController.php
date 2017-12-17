<?php

namespace backend\controllers;

use Yii;
use common\models\Shop;
use yii\db\Query;
use yii\web\BadRequestHttpException;

class ShopController extends \backend\lib\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        return $this->asJson(array(
            'list'  => array(),
            'total' => 0
        ));
    }

    public function actionEdit()
    {
        $id = self::getQuery('id');
        if ($id
            && !(new Query())->from('shop')
            ->where(['id' => $id])
            ->exists()
        ) {
            throw new BadRequestHttpException('店铺不存在或已删除');
        }

        if ($id) {
            $model = Shop::findOne($id);
        } else {
            $model = new Shop();
        }

        if (!Yii::$app->request->isPost) {
            $this->registerJsFile('shop/edit');
            return $this->render('edit', [
                'model' => $model,
            ]);
        }

        /**************** save *****************/
    }
}
