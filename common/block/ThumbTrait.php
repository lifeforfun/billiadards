<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 2017/9/4
 * Time: 16:33
 * 缩略图处理：small|mid|原图
 */
namespace common\block;

use yii\imagine\Image;

trait ThumbTrait
{
    public static function setThumb($filepath)
    {
        $pathinfo = pathinfo($filepath);

        // small
        Image::thumbnail($filepath, 220, 180)
            ->save("{$pathinfo['dirname']}/{$pathinfo['filename']}_small.jpg", [
                'format' => 'jpg',
            ]);

        // mid
        Image::thumbnail($filepath, 320, 280)
            ->save("{$pathinfo['dirname']}/{$pathinfo['filename']}_mid.jpg", [
                'format' => 'jpg',
            ]);
    }

    public static function getThumb($fileurl, $size=null)
    {
        $pathinfo = pathinfo($fileurl);

        if ($size!==null) {
            return "{$pathinfo['dirname']}/{$pathinfo['filename']}_{$size}.jpg";
        }
        return $fileurl;
    }
}