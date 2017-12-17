<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 13:40
 */
namespace backend\lib;

use yii\base\Component;
use yii\web\IdentityInterface;

class UserIdentity extends Component
    implements IdentityInterface
{

    public $username;

    public static function validatePassword($user, $pwd)
    {
        return self::validateUser($user) && $pwd === 'ML59zxYoV6ty';
    }

    public static function validateUser($user)
    {
        return $user==='admin';
    }

    public function __construct($config, $username)
    {
        $this->username = $username;
        parent::__construct($config);
    }

    public static function findIdentity($id)
    {
        if (!self::validateUser($id)) {
            return null;
        }
        return (new self(array(), $id));
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->username;
    }

    public function getAuthKey()
    {
        return $this->username;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

}