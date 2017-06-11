<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/9
 * Time: 9:29
 */
class EmchatClient
{
    // 企业id
    public $org;
    // 应用id
    public $app;

    public $gateway = 'https://a1.easemob.com/';

    private static $instance;

    public static function getInstance()
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }
        return self::$instance = new self();
    }

    /**
     * @param ApiRequest $res
     * @param string $token
     */
    public function execute($res, $token=null)
    {
        if (isset($token)) {
            $res->setHeader('Authorization', 'Bearer '.$token);
        }
        $header = $res->getHeader();
        $body = $res->getBody();

        switch ($res->getHttpMethod()) {
            case $res::HTTP_METHOD_GET:
                $setting = array(
                    CURLOPT_HTTPGET => true,
                );
                break;
            case $res::HTTP_METHOD_POST:
                $setting = array(
                    CURLOPT_POST => true,
                );
                break;
            default:
                $setting = array(
                    CURLOPT_CUSTOMREQUEST => $res->getHttpMethod()
                );
                break;
        }

        $resp = $this->curl(
            $res->getUrl($this->gateway.$this->org.'/'.$this->app) .'?'.http_build_query($res->getQuery()),
            $header,
            $body,
            $setting
        );

        return $resp['resp'];
    }

    /**
     * @param $url
     * @param array $header
     * @param array $body
     * @param array $setting
     */
    protected function curl($url, $header, $body, $setting=array())
    {
        $headers = array();
        foreach ($header as $k=>$v) {
            if (is_array($v)) {
                foreach ($v as $_v) {
                    $headers[] = $k.':'.$_v;
                }
            } else {
                $headers[] = $k.':'.$v;
            }
        }
        if (isset($setting[CURLOPT_POST]) && $setting[CURLOPT_POST] && !empty($body)) {
            $post = array();
            $postFile = false;
            foreach ($body as $k => $v) {
                if (strpos($v, '@')===true) {
                    $postFile = true;
                    $post[$k] = new CURLFile(substr($v, 1));
                } else {
                    $post[$k] = $v;
                }
            }
            if ($postFile) {
                $setting[CURLOPT_POSTFIELDS] = $post;
            } else {
                $setting[CURLOPT_POSTFIELDS] = http_build_query($post);
            }
        }


        $ch = curl_init($url);
        curl_setopt_array($ch, array_merge(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_MAXREDIRS => 3,
            //上传文件相关设置
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,// 对认证证书来源的检查
            CURLOPT_SSL_VERIFYPEER => false,// 从证书中检查SSL加密算
        ), $setting));

        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return array(
            'resp' => $resp,
            'info' => $info,
        );
    }
}