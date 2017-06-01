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


class LoginController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->request->isPost) {
            return $this->renderPartial('index');
        }

        $user = Yii::$app->request->post('user');
        $pwd = Yii::$app->request->post('pwd');
        $indentity = new UserIdentity(array(), $user);
        if (
        !$indentity::validatePassword($user, $pwd)
        ) {
            return $this->goBack();
        }
        Yii::$app->user->login($indentity);
        return $this->goHome();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}