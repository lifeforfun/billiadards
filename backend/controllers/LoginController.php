<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 18:08
 */
namespace backend\controllers;

use Yii;
use backend\lib\Controller;
use yii\filters\VerbFilter;


class LoginController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post']
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    public function actionLogin()
    {
        var_dump(Yii::$app->request->post());
    }
}