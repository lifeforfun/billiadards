<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 9:06
 */
namespace api\controllers;

use Yii;

class SiteController extends \api\lib\Controller
{
    public function actionIndex()
    {
        return 'test';
    }

    public function actionLogin()
    {
        return 'login';
    }

    /**
     * @desc 注册用户
     *
     * @return array
     */
    public function actionRegister()
    {

        $res = new \tokenRequest();
        $res->setClientId(Yii::$app->params['emchat']['client_id']);
        $res->setClientSecret(Yii::$app->params['emchat']['client_secret']);
        $res->setGrantType();
        $resp = self::execute($res);
        return 'register';
    }
}