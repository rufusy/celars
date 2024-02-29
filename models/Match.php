<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "matches".
 *
 * @property int $id
 * @property int $leagueId
 * @property int $homeTeamId
 * @property int $awayTeamId
 * @property string $dateTime
 * @property int $featured
 * @property int $free
 * @property string|null $tipsReasoning
 * @property int $statusId
 *
 * @property Team $awayTeam
 * @property Team $homeTeam
 * @property League $league
 * @property MatchTip[] $Tips
 * @property Status $status
 */
class Match extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'matches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['leagueId', 'homeTeamId', 'awayTeamId', 'dateTime', 'featured', 'free', 'statusId'], 'required'],
            [['leagueId', 'homeTeamId', 'awayTeamId', 'featured', 'free', 'statusId'], 'integer'],
            [['dateTime'], 'safe'],
            [['tipsReasoning'], 'string'],
            [['awayTeamId'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['awayTeamId' => 'id']],
            [['homeTeamId'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['homeTeamId' => 'id']],
            [['leagueId'], 'exist', 'skipOnError' => true, 'targetClass' => League::class, 'targetAttribute' => ['leagueId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'leagueId' => 'League ID',
            'homeTeamId' => 'Home Team ID',
            'awayTeamId' => 'Away Team ID',
            'dateTime' => 'Date Time',
            'featured' => 'Featured',
            'free' => 'Free',
            'tipsReasoning' => 'Tips Reasoning',
            'statusId' => 'Status ID',
        ];
    }

    /**
     * Gets query for [[AwayTeam]].
     *
     * @return ActiveQuery
     */
    public function getAwayTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'awayTeamId']);
    }

    /**
     * Gets query for [[HomeTeam]].
     *
     * @return ActiveQuery
     */
    public function getHomeTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'homeTeamId']);
    }

    /**
     * Gets query for [[League]].
     *
     * @return ActiveQuery
     */
    public function getLeague(): ActiveQuery
    {
        return $this->hasOne(League::class, ['id' => 'leagueId']);
    }

    /**
     * Gets query for [[Tips]].
     *
     * @return ActiveQuery
     */
    public function getTips(): ActiveQuery
    {
        return $this->hasMany(MatchTip::class, ['matchId' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['id' => 'statusId']);
    }
}
