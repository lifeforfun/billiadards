<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 9:06
 */
namespace api\controllers;

use Yii;
use JMessage\JMessage;
use JMessage\IM\User;

class SiteController extends \api\lib\Controller
{
    public function actionIndex()
    {
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

        $client = new JMessage(Yii::$app->params['jiguang']['appkey'], Yii::$app->params['jiguang']['secret']);
        $user = new User($client);

        return 'register';
    }
}