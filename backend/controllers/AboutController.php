<?php

namespace backend\controllers;

use common\models\Params;

class AboutController extends \backend\lib\Controller
{
    const PARAM_KEY = 'about';

    public function actionIndex()
    {
        $param = Params::findOne([
            'pkey' => self::PARAM_KEY
        ]);

        $this->registerJsFile('about/index.js');
        return $this->render('index', [
            'about' => $param ? $param->pvalue : ''
        ]);
    }

    public function actionSave()
    {
        $about = trim((string)self::getPost('about'));
        $param = Params::findOne([
            'pkey' => self::PARAM_KEY
        ]);
        if (!$param) {
            $param = new Params([
                'pkey' => self::PARAM_KEY
            ]);
        }
        $param->pvalue = $about;
        if (!$param->save()) {
            $errors = $param->getErrors();
            return $this->asJson([
                'status' => false,
                'msg' => current(current($errors)),
            ]);
        }
        $this->asJson([
            'status' => true
        ]);
    }
}
