<?php

namespace api\controllers;

use backend\controllers\AboutController;
use common\block\ThumbTrait;
use common\models\Feedback;
use common\models\Params;
use common\models\UploadFile;
use common\models\User;
use common\models\UserField;
use yii\db\Exception;
use yii\web\UploadedFile;

class MeController extends \api\lib\Controller
{
    use \common\block\SaveFileTrait;

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

    /**
     * 获取个人资料
     * @var integer $uid
     * @var string $session
     */
    public function actionGetProfile()
    {
       if (!self::checkLogin()) {
           return $this->asJson([
               'status' => false,
               'msg' => '登录失效'
           ]);
       }

       $user = User::findOne(['uid' => self::getPost('uid')]);
       $userField = UserField::findOne(['uname' => $user->uname]);

       if (!$userField) {
           $userField = new UserField([
               'uname' => $user->uname,
               'mobile' => $user->uname,
           ]);
           if (!$userField->save()) {
               $errors = $userField->getErrors();
               return $this->asJson([
                   'status' => false,
                   'msg' => current(current($errors))
               ]);
           }
       }

       $data = $userField->getAttributes(['uname', 'real_name', 'mobile', 'qq', 'gender', 'province', 'city', 'county']);
       $data['avatar'] = '';

       if ($userField->avatar) {
           $avatar = UploadFile::findOne(['id' => $userField->avatar]);
           $data['avatar'] = ThumbTrait::getThumb($avatar->url, 'small');
       }

       return $this->asJson([
           'status' => true,
           'data' => $data,
       ]);
    }

    /**
     * 修改个人资料
     * @var integer $uid
     * @var string $session
     * @var File $avatar 头像
     * @var string $real_name 姓名
     * @var string $mobile 手机
     * @var string $qq QQ
     * @var string $gender 性别
     * @var string $province 省
     * @var string $city 市
     * @var string $county 县
     */
    public function actionEditProfile()
    {
        if (!self::checkLogin()) {
            return $this->asJson([
                'status' => false,
                'msg' => '登录失效'
            ]);
        }

        $user = User::findOne(['uid' => self::getPost('uid')]);
        if (UserField::find()
            ->where(['uname' => $user->uname])
            ->exists()) {
            $userField = UserField::findOne(['uname' => $user->uname]);
        } else {
            $userField = new UserField([
                'uname' => $user->uname,
                'mobile' => $user->uname,
            ]);
        }

        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $avatar = UploadedFile::getInstanceByName('avatar');
            if ($avatar
                && !$avatar->hasError
                && in_array($avatar->extension, ['jpg', 'png', 'gif'])
                && ($resp = self::saveFile($avatar))) {

                $avatar = new UploadFile([
                    'filepath' => $resp['path'],
                    'url' => $resp['url'],
                    'type' => 'pic'
                ]);
                if ($avatar->save()) {

                    ThumbTrait::setThumb($resp['path']);
                    if ($userField->avatar
                        && $oldAvatar = UploadFile::findOne(['id' => $userField->avatar])) {

                        $oldAvatar->delete();
                        ThumbTrait::deleteThumb($oldAvatar->filepath);
                        if (is_file($oldAvatar->filepath)) {
                            unlink($oldAvatar->filepath);
                        }
                    }

                    $userField->avatar = $avatar->id;
                }
            }

            $userField->real_name = trim((string)self::getPost('real_name'));

            $mobile = (string)self::getPost('mobile');
            if (preg_match('/^1\d{10}$/', $mobile)) {
                $userField->mobile = $mobile;
            }

            $qq = trim((string)self::getPost('qq'));
            if ($qq==='' || preg_match('/^\d+$/', $qq)) {
                $userField->qq = $qq;
            }

            $userField->gender = trim((string)self::getPost('gender'));
            $userField->province = trim((string)self::getPost('province'));
            $userField->city = trim((string)self::getPost('city'));
            $userField->county = trim((string)self::getPost('county'));

            if (!$userField->save()) {
                $errors = $userField->errors;
                throw new Exception( current(current($errors)) );
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->asJson([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

        $transaction->commit();

        $data = $userField->getAttributes(null, ['avatar']);
        if ($userField->avatar) {
            $avatar = UploadFile::findOne(['id' => $userField->avatar]);
            $data['avatar'] = ThumbTrait::getThumb($avatar->url, 'small');
        }

        return $this->asJson([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * 意见反馈
     * @var integer $uid
     * @var string $session
     * @var string $content 反馈内容
     * @return array
     */
    public function actionFeedback()
    {
        if (!self::checkLogin()) {
            return $this->asJson([
                'status' => false,
                'msg' => '登录失效'
            ]);
        }
        $content = trim((string)self::getPost('content'));
        if ($content==='') {
            return $this->asJson([
                'status' => false,
                'msg' => '请填写反馈内容'
            ]);
        }

        $feed = new Feedback([
            'dateline' => date('Y-m-d H:i:s'),
            'uid' => self::getPost('uid'),
            'content' => $content
        ]);

        if (!$feed->save()) {
            $errors = $feed->getErrors();
            return $this->asJson([
                'status' => false,
                'msg' => current(current($errors))
            ]);
        }

        return $this->asJson([
            'status' => true,
        ]);
    }

    /**
     * 关于我们
     */
    public function actionAbout()
    {
        $about = Params::findOne([
            'pkey' => AboutController::PARAM_KEY
        ]);
        if (!$about) {
            return $this->asJson([
                'status' => true,
                'data' => [
                    'about' => '',
                ]
            ]);
        }
        return $this->asJson([
            'status' => true,
            'data' => [
                'about' => $about->pvalue
            ]
        ]);
    }
}
