<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12
 * Time: 10:31
 * 单个、批量用户注册, 采用授权注册方式
 */
class usersRegisterRequest extends ApiRequest
{
    public $method = 'users';

    public $http_method = self::HTTP_METHOD_POST;

    /**
     * 单用户注册
     * @param $username
     * @param $password
     * @param null $nickname
     *
     */
    public function setUser($username, $password, $nickname=null)
    {
        $this->body = array(
            'username' => $username,
            'password' => $password
        );
        if (isset($nickname)) {
            $this->body['nickname'] = $nickname;
        }
    }

    /**
     * 批量用户注册
     * @param array $users
     */
    public function setUsers($users)
    {
        $this->body = $users;
    }
}