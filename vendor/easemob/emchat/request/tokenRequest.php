<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/9
 * Time: 9:58
 * {
 * "access_token": "YWMt6CcHLEzhEeeO7OkcsDYlFAAAAAAAAAAAAAAAAAAAAAEivP-gj4YR5p34UW9t9oxtAgMAAAFci6tU7wBPGgCCtNrVplsu7UsbY5FFZZZHG-k9lESPnUZ0nKo7FDIzPw",
 * "expires_in": 4945338,
 * "application": "22bcffa0-8f86-11e6-9df8-516f6df68c6d"
 * }
 */
class tokenRequest extends ApiRequest
{
    public $method = 'token';

    public $http_method = self::HTTP_METHOD_POST;

    public function setGrantType($grant_type = 'client_credentials')
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