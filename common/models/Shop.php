<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shop".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $poi_id
 * @property string $phones
 * @property string $covers
 * @property string $description
 * @property string $longitude
 * @property string $latitude
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'poi_id', 'covers', 'description', 'longitude', 'latitude'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['address', 'poi_id', 'phones'], 'string', 'max' => 255],
            [['covers'], 'string', 'max' => 200],
            [['longitude', 'latitude'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '店铺名',
            'address' => '详细地址',
            'poi_id' => '百度地图位置数据id',
            'phones' => '联系电话,逗号分隔',
            'covers' => '封面图id列表,多个逗号分割',
            'description' => '店铺描述',
            'longitude' => '经度',
            'latitude' => '纬度',
        ];
    }
}
