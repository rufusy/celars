<?php

namespace app\traits;

use yii\helpers\ArrayHelper;

trait CountryTrait
{
    /**
     * @return array
     */
    private function activeCountries(): array
    {
        $countries = \app\models\Country::find()->select(['id', 'name'])
            ->where(['statusId' => $this->activeStatus()])->asArray()->all();
        return ArrayHelper::map($countries, 'id', function ($country){
            return $country['name'];
        });
    }
}