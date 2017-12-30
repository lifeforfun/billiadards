<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/3
 * Time: 15:37
 */
namespace common\block;

use Yii;

trait RequestTrait
{
    public static function getQuery($name=null, $defaultValue='')
    {
        return Yii::$app->request->get($name, $defaultValue);
    }

    public static function getPost($name=null, $defaultValue='')
    {
        return Yii::$app->request->post($name, $defaultValue);
    }

    /**
     * @param $url
     * @param $options
     * @return array
     * array(
            'status' => false,
     *      'msg' => 'failed reason',
     *      'resp' => 'response data'
     *      'req' => array('status_code'=>200,...), // curl_getinfo
     * )
     */
    public static function curl($url, $options=array())
    {
        $ret = array(
            'status' => false,
        );
        $ch = curl_init();
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_RETURNTRANSFER] = true;
        curl_setopt_array($ch, $options);
        $ret['resp'] = curl_exec($ch);
        $ret['req'] = curl_getinfo($ch);
        curl_close($ch);
        if ($ret['resp']===false) {
            $ret['msg'] = '['.curl_errno($ch).']'.curl_error($ch);
        }
        return $ret;
    }
}