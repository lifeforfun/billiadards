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
use Yii\Web\Controller as CController;

class Controller extends CController
{
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
            ],
            'matchCallback' => function() {
                Yii::$app->response->redirect(['login/index']);
                return true;
            }
        ];
    }
}