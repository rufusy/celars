<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "leagues_teams".
 *
 * @property int|null $leagueId
 * @property int|null $teamId
 */
class LeagueTeam extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'leagues_teams';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['leagueId', 'teamId'], 'integer'],
            [['leagueId'], 'exist', 'skipOnError' => true, 'targetClass' => League::class, 'targetAttribute' => ['leagueId' => 'id']],
            [['teamId'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['teamId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'leagueId' => 'League ID',
            'teamId' => 'Team ID',
        ];
    }

    /**
     * Gets query for [[League]].
     * @return ActiveQuery
     */
    public function getLeague(): ActiveQuery
    {
        return $this->hasOne(League::class, ['id' => 'leagueId']);
    }

    /**
     * Gets query for [[Team]].
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'teamId']);
    }
}
