<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 9:06
 */
namespace api\controllers;

use common\block\ThumbTrait;
use common\models\LoginSession;
use common\models\UploadFile;
use common\models\User;
use common\models\UserField;
use Yii;
use yii\base\Exception;
use yii\db\Query;

class SiteController extends \api\lib\Controller
{
    public function actionIndex()
    {
        return 'test';
    }

    /**
     * 登录
     * 1)当传入username参数时以用户名(username)/密码(password)登录
     * 2)否则以session登录，需要传入session/uid参数
     * @var $username 用户名
     * @var $password 密码
     * @var $uid 用户uid
     * @var $session 登录session
     * @return array
     */
    public function actionLogin()
    {
        $user = self::getPost('username');
        $pwd = self::getPost('password');

        if ($user) {
            // 以登录名登录
            if (!(new Query())->from('user')->where(['uname' => $user, 'pwd' => User::encrptPassword($pwd)])->exists()) {
                return $this->asJson([
                    'status' => false,
                    'msg' => '用户名或密码错误'
                ]);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = User::findOne(['uname' => $user]);
                $user->last_login = Yii::$app->params['datetime'];
                $loginSession = new LoginSession();
                $loginSession->uid = $user->uid;
                $loginSession->expires = date('Y-m-d H:i:s', Yii::$app->params['timestamp']+LoginSession::SESSION_EXPIRES);
                $loginSession->session = LoginSession::generateSession($user->uid);
                $loginSession->save();
                $user->save();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->asJson([
                    'status' => false,
                    'msg' => '服务器错误:'.$e->getMessage()
                ]);
            }

            $transaction->commit();

        } else {
            // 以登录hash登录
            $uid = self::getPost('uid');
            $session = self::getPost('session');

            if (!$uid || !$session) {
                return $this->asJson([
                    'status' => false,
                    'msg' => '[01]登录失效，请重新登录'
                ]);
            }

            if (!(new Query())
                ->from('login_session')
                ->where(['uid' => $uid])
                ->andWhere(['session' => $session])
                ->andWhere(['>', 'expires', Yii::$app->params['datetime']])
                ->exists()) {
                return $this->asJson([
                    'status' => false,
                    'msg' => '[02]登录失效，请重新登录'
                ]);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {

                $user = User::findOne(['uid' => $uid]);
                $user->last_login = Yii::$app->params['datetime'];
                $loginSession = LoginSession::findOne(['uid' => $uid, 'session' => $session]);
                $loginSession->session = LoginSession::generateSession($uid);
                $loginSession->expires = date('Y-m-d H:i:s', Yii::$app->params['timestamp']+LoginSession::SESSION_EXPIRES);
                $user->save();
                $loginSession->save();

            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->asJson([
                    'status' => false,
                    'msg' => '服务器错误:'.$e->getMessage()
                ]);
            }

            $transaction->commit();

        }

        $data = $loginSession->getAttributes();
        $data['avatar'] = '';
        $data['uname'] = $user->uname;
        if ($user->avatar) {
            $avatar = UploadFile::findOne(['id' => $user->avatar]);
            $data['avatar'] = ThumbTrait::getThumb($avatar->url, 'small');
        }

        return $this->asJson([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * @desc 注册用户
     *
     * @var $username
     * @var $password
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
            $db->pwd = User::encrptPassword($data['pwd']);
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

        $data = $db->attributes;
        unset($data['pwd']);
        return $this->asJson([
            'status' => true,
            'data' => $data
        ]);
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
        if ((new Query())
            ->from('user')
            ->where(['uname' => $uname])
            ->exists()) {
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