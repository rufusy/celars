<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "juzasports_media.units".
 *
 * @property int $id
 * @property string $name
 * @property int|null $type_id
 * @property UnitType $unitType
 */
class Unit extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'units';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['type_id'], 'integer'],
            [['name'], 'string', 'max' => 15],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitType::class, 'targetAttribute' => ['type_id' => 'id']],
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
            'type_id' => 'Type ID',
        ];
    }

    /**
     * Gets query for [[Unit Type]].
     *
     * @return ActiveQuery
     */
    public function getUnitType(): ActiveQuery
    {
        return $this->hasOne(UnitType::class, ['id' => 'type_id']);
    }
}
