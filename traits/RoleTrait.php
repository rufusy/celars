<?php

namespace app\traits;

use app\models\Role;
use app\models\RoleUser;

trait RoleTrait
{
    /**
     * @return int id of subscriber role
     */
    private function subscriberRole(): int
    {
        $role = Role::find()->where(['name' => 'subscriber'])->one();
        return $role->id;
    }

    /**
     * @return int id of admin role
     */
    private function adminRole(): int
    {
        $role = Role::find()->where(['name' => 'admin'])->one();
        return $role->id;
    }

    /**
     * @return int id of editor role
     */
    private function editorRole(): int
    {
        $role = Role::find()->where(['name' => 'editor'])->one();
        return $role->id;
    }

    /**
     * @param int $userId
     * @return bool true if user has a subscriber role. Else false;
     */
    private function isSubscriber(int $userId): bool
    {
        $roleUser = RoleUser::find()->where(['userId' => $userId, 'roleId' => $this->subscriberRole()])->one();
        if(is_null($roleUser)){
            return false;
        }
        return true;
    }

    /**
     * @param int $userId
     * @return bool true if user has an admin role. Else false;
     */
    private function isAdmin(int $userId): bool
    {
        $roleUser = RoleUser::find()->where(['userId' => $userId, 'roleId' => $this->adminRole()])->one();
        if(is_null($roleUser)){
            return false;
        }
        return true;
    }
}