<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 9:06
 */
namespace api\controllers;

use common\models\User;
use Yii;
use yii\base\Exception;

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
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $data = $this->checkPostRegister();

            $db = new User();
            $db->uname = $data['uname'];
            $db->nick = $db->uname;
            $db->pwd = $db->encrptPassword($data['pwd']);
            $db->created = Yii::$app->params['datetime'];
            $db->last_login = $db->created;
            $db->save();

            // 注册环信用户
            $res = new \usersRegisterRequest();
            $res->setUser($data['uname'], $data['pwd']);
            $resp = self::execute($res, self::getToken());
            $check = self::checkApiResult($res, $resp);
            if (!$check['status']) {
                throw new Exception($check['msg']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->asJson([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

        $transaction->commit();

        return $this->asJson($db->attributes);
    }

    protected function checkPostRegister()
    {
        $uname = trim(self::getPost('username'));
        $pwd = self::getPost('password');
        if ($uname==='' ||
            iconv_strlen($uname, 'UTF-8')>30 ||
            !preg_match('/^1\d{10}$/', $uname)) {
            throw new Exception('用户名必须为11位手机号码');
        }
        if ((new User)->hasOne(User::className(), ['uname' => $uname])) {
            throw new Exception('用户名已存在');
        }
        if ($pwd==='') {
            throw new Exception('请输入密码');
        }

        return array(
            'uname' => $uname,
            'pwd' => $pwd,
        );
    }
}