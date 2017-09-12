<?php

namespace api\controllers;

use common\block\ThumbTrait;
use common\models\UploadFile;
use common\models\User;

class ChatController extends \api\lib\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取聊天人昵称、头像
     * @var integer $uid
     */
    public function actionGetNick()
    {
        $user = User::findOne(['uid' => self::getPost('uid')]);
        if (!$user) {
            return $this->asJson([
                'status' => false,
                'msg' => '用户不存在'
            ]);
        }

        $data = $user->getAttributes(['uid', 'uname', 'avatar', 'nick']);
        if ($data['avatar']) {
            $avatar = UploadFile::findOne(['id' => $data['avatar']]);
            $data['avatar'] = ThumbTrait::getThumb($avatar->url, 'small');
        } else {
            $data['avatar'] = '';
        }

        return $this->asJson([
            'status' => true,
            'data' => $data,
        ]);
    }
}
