<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "leagues".
 *
 * @property int $id
 * @property string $name
 * @property int $statusId
 *
 * @property LeagueTeam $teams
 */
class League extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'leagues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['countryId', 'statusId'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'countryId' => 'Country ID',
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
        return $this->hasMany(LeagueTeam::class, ['leagueId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['id' => 'statusId']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }
}
