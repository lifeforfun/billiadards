<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 17:49
 */
namespace common\block;

use Yii;
use yii\web\View;

trait RegisterAssetsTrait {

    /**
     * @var $this \yii\web\Controller
     * Registers a JS file.
     * @param string $filepath the JS file to be registered.
     */
    public function registerJsFile($filepath)
    {
        static $registerCommonFile = false;

        if (!$registerCommonFile) {
            $registerCommonFile = true;
            $this->registerJsFile('common.js');
            // 注册createUrl
            $base_uri = Yii::$app->urlManager->baseUrl;
            $this->view->registerJs(<<<JS
                var base_uri = '{$base_uri}'
                function createUrl(route, query) {
                  if (typeof route!=='string') {
                    query = route
                    route = '/'
                  }
                  if (typeof query!=='object') {
                      query = {}
                  }
                  var url = base_uri+'/index.php?'
                  query.r = route
                  for(var i in query) {
                      url += i+'='+encodeURIComponent(query[i])+'&'
                  }
                  return url
                }
JS
                ,
                View::POS_HEAD
);
        }

        $realpath = Yii::getAlias('@webroot/dist/'.ltrim($filepath, '/'));
        $filepath = '@web/dist/'.ltrim($filepath, '/');
        if (!is_file($realpath)) {
            return;
        }
        $v = substr(md5_file($realpath), 0, 6);
        return $this->view->registerJsFile($filepath . '?v=' . $v, [
            'depends' => [
                'yii\web\YiiAsset',
            ]
        ]);
    }
}