<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "upload_file".
 *
 * @property integer $id
 * @property string $filepath
 * @property string $url
 * @property string $type
 */
class UploadFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filepath', 'url', 'type'], 'required'],
            [['filepath', 'url'], 'string', 'max' => 300],
            [['type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filepath' => '本地地址',
            'url' => '访问地址',
            'type' => '文件类型:video/pic/other',
        ];
    }
}
