<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 17:49
 */
namespace common\block;

trait RegisterAssetsTrait {

    /**
     * Registers a JS file.
     * @param string $filepath the JS file to be registered.
     */
    public function registerJsFile($filepath)
    {
        $filepath = Yii::getRootAlias();
        var_dump($filepath);exit;
        return $this->view->registerJsFile($filepath, ['depends' => 'yii\web\YiiAsset']);
    }
}