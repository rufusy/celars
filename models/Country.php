<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $name
 * @property int $statusId
 *
 * @property Team[] $teams
 */
class Country extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['statusId'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'statusId' => 'Status ID',
        ];
    }

    /**
     * Gets query for [[Teams]].
     *
     * @return ActiveQuery
     */
    public function getTeams(): ActiveQuery
    {
        return $this->hasMany(Team::class, ['countryId' => 'id']);
    }

    /**
     * Gets query for [[Leagues]].
     * @return ActiveQuery
     */
    public function getLeagues(): ActiveQuery
    {
        return $this->hasMany(League::class, ['countryId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['id' => 'statusId']);
    }
}
