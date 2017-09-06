<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/9
 * Time: 9:40
 */
abstract class ApiRequest
{
    public $method = '';

    public $http_method = self::HTTP_METHOD_GET;

    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_DELETE = 'DELETE';
    const HTTP_METHOD_HEAD = 'HEAD';
    const HTTP_METHOD_PUT = 'PUT';

    /**
     * @var array
     * array(
        'cache-expire' => '1day',
     *  'set-cookie' => array(
            'coa=1; path=/',
     *      'cob=2; path=/',
     *   )
     * )
     */
    public $header = array(
        'content-type' => 'application/json',
        'accept' => 'application/json'
    );

    /**
     * @var array 跟在url后的queryString
     */
    public $query = array();

    public $body = array();

    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $key
     * @param string $value
     * @param bool $replace 是否覆盖已存在的头信息, 如果为false，则会将请求值存储为数组
     */
    public function setHeader($key, $value, $replace=true)
    {
        $key = strtolower($key);
        if (isset($this->header[$key])) {
            if ($replace) {
                $this->header[$key] = $value;
            } else {
                $this->header[$key] = array_merge(
                    is_array($this->header[$key]) ? $this->header[$key] : array($this->header[$key]),
                    array($value));
            }
        } else {
            $this->header[$key] = $value;
        }
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getApiMethod()
    {
        return $this->method;
    }

    /**
     * 获取curl请求地址(不带query串)，子类可覆盖此方法实现更深层次的url拼接
     * @param string $url https://a1.easemob.com/{ORG_NAME}/{APP_NAME}
     */
    public function getUrl($url)
    {
        return rtrim($url, '/').'/'.$this->getApiMethod();
    }

    public function getHttpMethod()
    {
        return $this->http_method;
    }
}