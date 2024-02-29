<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "juzasports_media.packages".
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property string $period
 * @property int|null $periodUnitId
 * @property int|null $priceUnitId
 * @property Unit $periodUnit
 * @property Unit $priceUnit
 */
class Package extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'packages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'price', 'period'], 'required'],
            [['periodUnitId', 'priceUnitId'], 'integer'],
            [['name', 'price', 'period'], 'string', 'max' => 45],
            [['periodUnitId'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['periodUnitId' => 'id']],
            [['priceUnitId'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['priceUnitId' => 'id']],
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
            'price' => 'Price',
            'period' => 'Period',
            'periodUnitId' => 'Period Unit ID',
            'priceUnitId' => 'Price Unit ID',
        ];
    }

    /**
     * Gets query for [[Period Unit]].
     *
     * @return ActiveQuery
     */
    public function getPeriodUnit(): ActiveQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'periodUnitId']);
    }

    /**
     * Gets query for [[Price Unit]].
     *
     * @return ActiveQuery
     */
    public function getPriceUnit(): ActiveQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'priceUnitId']);
    }
}
