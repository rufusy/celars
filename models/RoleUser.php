<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "roles_users".
 *
 * @property int|null $roleId
 * @property int|null $userId
 *
 * @property Role $role
 * @property User $user
 */
class RoleUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'roles_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['roleId', 'userId'], 'integer'],
            [['roleId'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['roleId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'roleId' => 'Role ID',
            'userId' => 'User ID',
        ];
    }
}
