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

    public function init()
    {
        parent::init();
        require(Yii::getAlias('@jmessage/autoload.php'));
    }

    public function behaviors()
    {
        return [];
    }
}