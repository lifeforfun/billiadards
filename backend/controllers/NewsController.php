<?php
/**
 * Created by PhpStorm.
 * User: ff
 * Date: 5/29/17
 * Time: 5:44 PM
 */
namespace backend\controllers;

use backend\lib\Controller;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $this->registerJsFile('news/index.js');
        return $this->render('index');
    }
}