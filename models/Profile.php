<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property int $userId
 * @property string|null $introBio
 * @property string|null $fullBio
 * @property string|null $imageUrl
 * @property string|null $mobileNumber
 * @property string|null $facebookUrl
 * @property string|null $twitterUrl
 * @property string|null $instagramUrl
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $deleted
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId', 'status'], 'integer'],
            [['introBio', 'fullBio'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['imageUrl', 'facebookUrl', 'twitterUrl', 'instagramUrl'], 'string', 'max' => 255],
            [['mobileNumber'], 'string', 'max' => 10],
            [['imageUrl'], 'unique'],
            [['mobileNumber'], 'unique'],
            [['facebookUrl'], 'unique'],
            [['twitterUrl'], 'unique'],
            [['instagramUrl'], 'unique'],
            // [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'introBio' => 'Intro Bio',
            'fullBio' => 'Full Bio',
            'imageUrl' => 'Image Url',
            'mobileNumber' => 'Mobile Number',
            'facebookUrl' => 'Facebook Url',
            'twitterUrl' => 'Twitter Url',
            'instagramUrl' => 'Instagram Url',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
