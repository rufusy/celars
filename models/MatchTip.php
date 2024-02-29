<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "match_tips".
 *
 * @property int $matchId
 * @property int $tipId
 *
 * @property Match $match
 * @property Tip $tip
 */
class MatchTip extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'match_tips';
    }

    /**
     * {@inheritdoc}
     */
    public static function primaryKey(): array
    {
        return ['matchId', 'tipId'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['matchId', 'tipId'], 'required'],
            [['matchId', 'tipId'], 'integer'],
            [['matchId'], 'exist', 'skipOnError' => true, 'targetClass' => Match::class, 'targetAttribute' => ['matchId' => 'id']],
            [['tipId'], 'exist', 'skipOnError' => true, 'targetClass' => Tip::class, 'targetAttribute' => ['tipId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'matchId' => 'Match ID',
            'tipId' => 'Tip ID',
        ];
    }

    /**
     * Gets query for [[Match]].
     *
     * @return ActiveQuery
     */
    public function getMatch(): ActiveQuery
    {
        return $this->hasOne(Match::class, ['id' => 'matchId']);
    }

    /**
     * Gets query for [[Tip]].
     *
     * @return ActiveQuery
     */
    public function getTip(): ActiveQuery
    {
        return $this->hasOne(Tip::class, ['id' => 'tipId']);
    }
}
