<?php

namespace backend\controllers;

use Yii;
use common\models\Shop;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

class ShopController extends \backend\lib\Controller
{
    private $gateway = 'http://api.map.baidu.com/geodata/v3';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        return $this->asJson(array(
            'list'  => array(),
            'total' => 0
        ));
    }

    public function actionEdit()
    {
        $id = self::getQuery('id');
        if ($id
            && !(new Query())->from('shop')
                ->where(['id' => $id])
                ->exists()
        ) {
            throw new BadRequestHttpException('店铺不存在或已删除');
        }

        if ($id) {
            $model = Shop::findOne($id);
        } else {
            $model = new Shop();
        }

        if (!Yii::$app->request->isPost) {
            $this->getView()->registerJsFile(
                '//api.map.baidu.com/api?ak='
                . Yii::$app->params['bmap']['ak_web']
                . '&v=3.0');
            $this->registerJsFile('shop/edit.js');
            return $this->render('edit', [
                'model' => $model,
            ]);
        }

        /**************** save *****************/
        $data = self::getPost();
    }

    private function getGateWay($api, $data = array())
    {
        return $this->gateway . $api
            . (empty($data) ? '' : http_build_query($data));
    }

    private function checkResp($resp)
    {
        if (!$resp['status']) {
            throw new HttpException($resp['msg'], 500);
        }
        $respDecode = json_decode($resp['resp'], true);
        if (!$respDecode) {
            throw new HttpException('invalid response', 500);
        }
        if ($respDecode['status']) {
            throw new HttpException($respDecode['message'], 500);
        }
        return $respDecode;
    }

    protected function getRecord($id)
    {
        $resp = self::curl($this->getGateWay('/poi/detail', array(
            'id'          => $id,
            'ak'          => Yii::$app->params['bmap']['ak'],
            'geotable_id' => Yii::$app->params['bmap']['geotable_id'],
        )));
        return $this->checkResp($resp);
    }

    protected function setRecord($data)
    {
        $data['ak']          = Yii::$app->params['bmap']['ak'];
        $data['geotable_id'] = Yii::$app->params['bmap']['geotable_id'];
        $resp                = self::curl(
            $this->getGateWay(isset($data['id']) ? '/poi/update' : '/poi/create'),
            array(
                CURLOPT_PORT       => true,
                CURLOPT_POSTFIELDS => http_build_query($data)
            )
        );
        $respDecode          = $this->checkResp($resp);
        return isset($data['id']) ? $data['id'] : $respDecode['id'];
    }

    protected function delRecord($id)
    {
        $resp = self::curl(
            $this->getGateWay('/poi/delete'),
            array(
                CURLOPT_POST       => true,
                CURLOPT_POSTFIELDS => http_build_query(array(
                    'id'          => $id,
                    'ak'          => Yii::$app->params['bmap']['ak'],
                    'geotable_id' => Yii::$app->params['bmap']['geotable_id'],
                ))
            )
        );
    }
}
