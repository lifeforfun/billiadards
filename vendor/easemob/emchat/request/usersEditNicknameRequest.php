<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 2017/9/12
 * Time: 17:00
 */
class usersEditNicknameRequest extends ApiRequest
{
    public $method = 'users';

    public $http_method = self::HTTP_METHOD_PUT;

    private $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function getApiMethod()
    {
        return parent::getApiMethod() . '/' . $this->username;
    }

    public function setNickname($nickname)
    {
        $this->body['nickname'] = $nickname;
    }
}