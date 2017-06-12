<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $uid
 * @property string $uname
 * @property string $pwd
 * @property string $nick
 * @property string $created
 * @property string $last_login
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uname', 'pwd', 'nick', 'created', 'last_login'], 'required'],
            [['created', 'last_login'], 'safe'],
            [['uname', 'nick'], 'string', 'max' => 40],
            [['pwd'], 'string', 'max' => 255],
            [['uname'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'uname' => '邮箱或手机号码',
            'pwd' => '密码',
            'nick' => '昵称',
            'created' => '注册时间',
            'last_login' => '最近登录时间',
        ];
    }

    public function encrptPassword($pwd)
    {
        return md5($pwd);
    }
}
