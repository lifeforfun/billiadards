<?php

namespace api\controllers;

use common\models\User;

class MeController extends \api\lib\Controller
{
    /**
     * 修改密码
     * @var integer $uid
     * @var string $session
     * @var string $pwd 旧密码
     * @var string $new_pwd 新密码
     * @return array
     */
    public function actionChangePassword()
    {
        if (!self::checkLogin()) {
            return $this->asJson([
                'status' => false,
                'msg' => '登录失效'
            ]);
        }

        $pwd = (string)self::getPost('pwd');
        $new_pwd = (string)self::getPost('new_pwd');

        if ($pwd==='') {
            return $this->asJson([
                'status' => false,
                'msg' => '请填写旧密码'
            ]);
        }

        if ($new_pwd==='') {
            return $this->asJson([
                'status' => false,
                'msg' => '请填写新密码'
            ]);
        }

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $user = User::findOne(['uid' => self::getPost('uid')]);
            if ($user->pwd != User::encrptPassword($pwd)) {
                throw new \Exception('旧密码错误');
            }
            $user->pwd = User::encrptPassword($new_pwd);
            if (!$user->save()) {
                throw new \Exception('密码修改失败');
            }

            $res = new \usersPasswordRequest($user->uname);
            $res->setNewpassword($new_pwd);
            $resp = self::execute($res, self::getToken());
            $check = self::checkApiResult($res, $resp);
            if (!$check['status']) {
                throw new \Exception($check['msg']);
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->asJson([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

        $transaction->commit();

        return $this->asJson([
            'status' => true
        ]);
    }

}
