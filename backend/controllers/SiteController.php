<?php
namespace backend\controllers;

use Yii;
use backend\lib\Controller;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\BaseImage;

/**
 * Site controller
 */
class SiteController extends Controller
{

    use \common\block\SaveFileTrait;

    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'login' => ['get', 'post'],
                    'upload' => ['post']
                ]
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    public function actionUploadImage()
    {
        $uploader = UploadedFile::getInstanceByName('img');
        if (!$uploader) {
            return $this->asJson([
                'errno' => 1,
                'msg' => '图片上传失败',
            ]);
        }
        $image = getimagesize($uploader->tempName);
        if (!$image) {
            return $this->asJson([
                'errno' => 1,
                'msg' => '上传文件必须是图片',
            ]);
        }

        if (!($saved = $this->saveFile($uploader))) {
            return $this->asJson([
                'errno' => 1,
                'msg' => '图片文件保存失败'
            ]);
        }
        return $this->asJson([
            'errno' => 0,
            'data' => [ $saved['url'] ]
        ]);
    }
}
