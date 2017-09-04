<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property string $id
 * @property integer $status
 * @property string $title
 * @property string $dateline
 * @property string $tag
 * @property string $cover
 * @property integer $vid
 * @property string $pids
 * @property string $content
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['title', 'dateline', 'content'], 'required'],
            [['dateline'], 'safe'],
            [['vid'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 60],
            [['tag'], 'string', 'max' => 255],
            [['cover', 'pids'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => '审核状态',
            'title' => '文章标题',
            'dateline' => '发布日期',
            'tag' => '标签列表:|关键词1|关键词2|...|',
            'cover' => '封面图url',
            'vid' => '上传视频文件id',
            'pids' => '上传图片文件id列表，英文逗号分隔',
            'content' => '文章内容',
        ];
    }

}
