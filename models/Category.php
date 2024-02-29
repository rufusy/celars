<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int|null $parentId
 * @property string $title
 * @property string|null $metaTitle
 * @property string $slug
 * @property string|null $content
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $deleted
 *
 * @property Category[] $categories
 * @property CategoriesPosts[] $categoriesPosts
 * @property Category $parent
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parentId', 'deleted'], 'integer'],
            [['title', 'slug'], 'required'],
            [['content'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title', 'metaTitle', 'slug'], 'string', 'max' => 255],
            [['title'], 'unique'],
            [['parentId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parentId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parentId' => 'Parent ID',
            'title' => 'Title',
            'metaTitle' => 'Meta Title',
            'slug' => 'Slug',
            'content' => 'Content',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parentId' => 'id']);
    }

    /**
     * Gets query for [[CategoriesPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesPosts()
    {
        return $this->hasMany(CategoriesPosts::className(), ['categoryId' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parentId']);
    }
}
