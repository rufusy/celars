<?php

namespace app\models;

use yii\base\Model;

class TeamsUpload extends Model
{
    public $countryId;
    public $teamsFile;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['countryId'], 'required'],
            [['teamsFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx, xls'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'countryId' => 'Country',
            'teamsFile' => 'Teams file'
        ];
    }
}