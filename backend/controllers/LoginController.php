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
use backend\lib\UserIdentity;
use yii\filters\VerbFilter;
use yii\helpers\Url;


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
        $user = Yii::$app->request->post('user');
        $pwd = Yii::$app->request->post('pwd');
        $indentity = new UserIdentity(array(), $user);
        if (
            !$indentity::validatePassword($user, $pwd)
        ) {
            $this->redirect(Url::to(['index']));
            Yii::$app->end();
        }
        Yii::$app->user->login($indentity);
        $this->redirect(['site/index']);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}