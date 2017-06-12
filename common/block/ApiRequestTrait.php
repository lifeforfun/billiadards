<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12
 * Time: 9:12
 */
namespace common\block;

use Yii;
use yii\log\Logger;

trait ApiRequestTrait
{
    public static function getClient()
    {
        return \EmchatClient::getInstance();
    }

    /**
     * @param \ApiRequest $res
     * @param string $token
     */
    public static function execute($res, $token=null)
    {
        return self::getClient()->execute($res, $token);
    }

    public static function getToken()
    {
        static $cacheKey = 'emchat_api_token';
        $token = Yii::$app->cache->get($cacheKey);
        if ($token) {
            return $token;
        }
        $res = new \tokenRequest();
        $res->setClientId(Yii::$app->params['emchat']['client_id']);
        $res->setClientSecret(Yii::$app->params['emchat']['client_secret']);
        $res->setGrantType();
        $resp = self::execute($res);
        $check = self::checkApiResult($res, $resp);
        if (!$check['status']) {
            Yii::$app->log->logger->log($check['msg'], Logger::LEVEL_WARNING);
            return false;
        }
        Yii::$app->cache->set($cacheKey,
            $resp->access_token,
            $resp->expires_in-1);
        return $resp->access_token;
    }

    /**
     * @param \ApiRequest $res
     * @param object $resp
     */
    public static function checkApiResult($res, $resp)
    {
        if (!$resp) {
            return array(
                'status' => false,
                'msg' => '['.$res->getApiMethod().'] request failed.'
            );
        }
        if (isset($resp->error)) {
            return array(
                'status' => false,
                'msg' => '['.$res->getApiMethod().']' . $resp->error . (isset($resp->error_description) ? '- -'.$resp->error_description : '')
            );
        }
        return array(
            'status' => true
        );
    }
}