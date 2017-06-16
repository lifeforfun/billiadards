<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property string $id
 * @property string $title
 * @property string $dateline
 * @property string $tag
 * @property \yii\web\UploadedFile $cover
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
            [['title', 'dateline', 'content'], 'required'],
            [['dateline'], 'safe'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 60],
            [['tag'], 'string', 'max' => 255],
            [['cover'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '文章标题',
            'dateline' => '发布日期',
            'tag' => '标签列表:|关键词1|关键词2|...|',
            'cover' => '封面图url',
            'content' => '文章内容',
        ];
    }

    public function getCover($size=null)
    {
        if ($size===null) {

        }
    }

    public function getTag()
    {
        return explode('|', trim($this->tag, '|'));
    }

    public function setTag(array $tags)
    {
        $this->tag = empty($tags) ? '' : ('|' . implode('|', $tags) . '|');
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->cover->saveAs('uploads/'. $this->cover->baseName . '.' . $this->cover->extension);
            return true;
        } else {
            return false;
        }
    }
}
