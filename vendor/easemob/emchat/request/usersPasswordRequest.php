<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 2017/9/6
 * Time: 16:53
 */
class usersPasswordRequest extends ApiRequest
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
        return 'users/' . $this->username . '/password';
    }

    public function setNewpassword($newpassword)
    {
        $this->body = array(
            'newpassword' => $newpassword
        );
    }
}