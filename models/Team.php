<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "teams".
 *
 * @property int $id
 * @property string $name
 * @property string|null $badge
 * @property int|null $countryId
 * @property int $statusId
 *
 * @property Country $country
 * @property LeagueTeam $leagues
 */
class Team extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'teams';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'countryId'], 'required'],
            [['countryId', 'statusId'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['badge'], 'string', 'max' => 255],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
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
            'badge' => 'Badge',
            'countryId' => 'Country',
            'statusId' => 'Status ID',
        ];
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

    /**
     * Gets query for [[Leagues]].
     *
     * @return ActiveQuery
     */
    public function getLeagues(): ActiveQuery
    {
        return $this->hasMany(LeagueTeam::class, ['teamId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['id' => 'statusId']);
    }
}
