<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tips".
 *
 * @property int $id
 * @property string $name
 *
 * @property MatchTip[] $matches
 */
class Tip extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Matches]].
     *
     * @return ActiveQuery
     */
    public function getMatches(): ActiveQuery
    {
        return $this->hasMany(MatchTip::class, ['tipId' => 'id']);
    }
}
