<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 17:52
 */
namespace api\lib;

use yii\web\Controller as CController;
use Yii;

class Controller extends CController
{
    use \common\block\RequestTrait;
    use \common\block\ApiRequestTrait;

    public function init()
    {
        parent::init();

        // 关闭csrf
        $this->enableCsrfValidation = false;

        // init api client configuration
        require(Yii::getAlias('@emchat/autoload.php'));
        $client = self::getClient();
        $client->org = Yii::$app->params['emchat']['org'];
        $client->app = Yii::$app->params['emchat']['app'];
    }

    public function behaviors()
    {
        return [];
    }
}