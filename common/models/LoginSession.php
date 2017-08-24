<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "login_session".
 *
 * @property integer $uid
 * @property string $session
 * @property string $expires
 */
class LoginSession extends \yii\db\ActiveRecord
{
    /**
     * session过期时间(秒)
     */
    const SESSION_EXPIRES = 864e3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'login_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'session', 'expires'], 'required'],
            [['uid'], 'integer'],
            [['expires'], 'safe'],
            [['session'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'session' => 'Session',
            'expires' => '过期时间',
        ];
    }

    public static function generateSession($uid)
    {
        return md5($uid.Yii::$app->params['timestamp']);
    }
}
