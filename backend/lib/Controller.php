<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 17:52
 */
namespace backend\lib;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller as CController;


class Controller extends CController
{
    use \common\block\RegisterAssetsTrait;
    use \common\block\RequestTrait;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function() {
                    Yii::$app->response->redirect(['login/index']);
                    return true;
                }
            ],
        ];
    }
}