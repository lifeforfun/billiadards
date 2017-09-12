<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_field".
 *
 * @property string $uname
 * @property string $real_name
 * @property string $mobile
 * @property string $qq
 * @property string $gender
 * @property string $province
 * @property string $city
 * @property string $county
 */
class UserField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uname'], 'required'],
            [['uname'], 'string', 'max' => 40],
            [['real_name'], 'string', 'max' => 255],
            [['mobile', 'province', 'city', 'county'], 'string', 'max' => 30],
            [['qq'], 'string', 'max' => 20],
            [['gender'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uname' => 'Uname',
            'real_name' => '真实姓名',
            'mobile' => '电话',
            'qq' => 'QQ',
            'gender' => '性别(mael:男,female:女)',
            'province' => '地区',
            'city' => '市',
            'county' => '县',
        ];
    }
}
