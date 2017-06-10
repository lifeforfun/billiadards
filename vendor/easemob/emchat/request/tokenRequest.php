<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/9
 * Time: 9:58
 */
class tokenRequest extends ApiRequest
{
    public $method = 'token';

    public $http_method = self::HTTP_METHOD_POST;

    public function setGrantType($grant_type='client_credentials')
    {
        $this->body['grant_type'] = $grant_type;
    }

    public function setClientId($client_id)
    {
        $this->body['client_id'] = $client_id;
    }

    public function setClientSecret($client_secret)
    {
        $this->body['client_secret'] = $client_secret;
    }
}