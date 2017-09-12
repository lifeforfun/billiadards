<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 2017/8/31
 * Time: 21:33
 */
namespace common\block;

use yii\web\UploadedFile;

trait SaveFileTrait
{
    /**
     * @param UploadedFile $file
     * @return array
     * array( 'url' => 文件访问地址, 'path' => 文件保存地址)
     */
    public function saveFile($file)
    {
        $filename = md5($file->tempName . microtime(true)) . '.' . $file->extension;
        $filepath = dirname(dirname(__DIR__)) . '/upload/';
        $absFilepath = $filepath . $filename;
        if (!$file->saveAs($absFilepath)) {
            return false;
        }
        return [
            'url' => \Yii::$app->params['file_url'] . $filename,
            'path' => $absFilepath,
        ];
    }
}