<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "juzasports_media.subscriptions".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $packageTypeId
 * @property string $startDate
 * @property string $enddate
 * @property User $user
 * @property Package $package
 */
class Subscription extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'subscriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'packageTypeId'], 'integer'],
            [['startDate', 'enddate'], 'required'],
            [['startDate', 'enddate'], 'safe'],
            [['packageTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => Package::class, 'targetAttribute' => ['packageTypeId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'packageTypeId' => 'Package Type ID',
            'startDate' => 'Start Date',
            'enddate' => 'Enddate',
        ];
    }

    /**
     * Gets query for [[package]].
     *
     * @return ActiveQuery
     */
    public function getPackage(): ActiveQuery
    {
        return $this->hasOne(Package::class, ['id' => 'packageTypeId']);
    }

    /**
     * Gets query for [[subscriber]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }
}
