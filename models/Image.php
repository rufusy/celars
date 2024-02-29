<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property int|null $postId
 * @property string $name
 * @property string $url
 * @property int $deleted
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Posts $post
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['postId', 'deleted'], 'integer'],
            [['name', 'url'], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name', 'url'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['postId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'postId' => 'Post ID',
            'name' => 'Name',
            'url' => 'Url',
            'deleted' => 'Deleted',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'postId']);
    }
}
