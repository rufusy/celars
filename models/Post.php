<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "posts_celars".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $body
 * @property string $image_url
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $published_at
 * @property string|null $deleted_at
 */
class Post extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'posts_celars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'slug', 'body', 'image_url'], 'required'],
            [['body'], 'string'],
            [['created_at', 'updated_at', 'published_at', 'deleted_at'], 'safe'],
            [['title', 'slug', 'image_url'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'body' => 'Body',
            'image_url' => 'Image Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'published_at' => 'Published At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
