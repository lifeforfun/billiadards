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
}