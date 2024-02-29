<?php

namespace app\models;

use Exception;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string|null $emailVerifiedAt
 * @property string $password
 * @property string $introBio
 * @property string $fullBio
 * @property string $verifyToken
 * @property string $mobileNumber
 * @property string $facebookUrl
 * @property string $twitterUrl
 * @property string $instagramUrl
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $statusId
 * @property string $timezone
 * @property string $country
 *
 * @property Post[] $posts
 * @property RoleUser[] $roles
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['firstName', 'lastName', 'email', 'password', 'createdAt', 'updatedAt', 'timezone'], 'required'],
            [['emailVerifiedAt', 'createdAt', 'updatedAt'], 'safe'],
            [['introBio', 'fullBio'], 'string'],
            [['statusId'], 'integer'],
            [['firstName', 'lastName', 'email'], 'string', 'max' => 50],
            [['password', 'verifyToken', 'facebookUrl', 'twitterUrl', 'instagramUrl', 'country'], 'string', 'max' => 255],
            [['mobileNumber'], 'string', 'max' => 15],
            [['timezone'], 'string', 'max' => 45],
            [['email', 'mobileNumber'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
            'emailVerifiedAt' => 'Email Verified At',
            'password' => 'Password',
            'introBio' => 'Intro Bio',
            'fullBio' => 'Full Bio',
            'verifyToken' => 'Verify token',
            'mobileNumber' => 'Mobile Number',
            'facebookUrl' => 'Facebook Url',
            'twitterUrl' => 'Twitter Url',
            'instagramUrl' => 'Instagram Url',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'statusId' => 'Status Id',
            'timezone' => 'Time Zone',
            'country' => 'Country'
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if($insert){
            $this->createdAt = new Expression('CURRENT_DATE');
        }
        $this->updatedAt = new Expression('CURRENT_DATE');

        if(empty($this->timezone)){
            $this->timezone = 'Africa/Nairobi';
        }

        if(empty($this->country)){
            $this->country = 'Kenya';
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null){}

    /**
     * Finds user by username
     *
     * @param string $username
     * @return ActiveRecord|array|null
     */
    public static function findByUsername(string $username): ?User
    {
        return self::find()->where(['email' => $username])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(){}

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey){}


    /**
     * @return array
     * @throws Exception
     */
    public function generatePassword(): array
    {
        $passwordMaker = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz-*/_$#()&!+';
        $plainPassword = substr(str_shuffle($passwordMaker), 0, 8);

        try{
            $hashPassword = Yii::$app->getSecurity()->generatePasswordHash($plainPassword);
        }catch(Exception $ex){
            $message = 'Failed to generate password hash.';
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new Exception($message);
        }

        return [
            'plain' => $plainPassword,
            'hash' => $hashPassword
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateVerifyToken(): string
    {
        $tokenMaker = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz-*/_$#()&!+';
        $plainToken = substr(str_shuffle($tokenMaker), 0, 15);

        try{
            $hashToken = Yii::$app->getSecurity()->generatePasswordHash($plainToken);
        }catch(Exception $ex){
            $message = 'Failed to generate account verification token.';
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new Exception($message);
        }

        return $hashToken;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        if (Yii::$app->getSecurity()->validatePassword($password, $this->password)) {
            return true;
        } else{
            return false;
        }
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return ActiveQuery
     */
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['authorId' => 'id']);
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return ActiveQuery
     */
    public function getProfile(): ActiveQuery
    {
        return $this->hasOne(Profile::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Roles]].
     *
     * @return ActiveQuery
     */
    public function getRoles(): ActiveQuery
    {
        return $this->hasMany(RoleUser::class, ['userId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['id' => 'statusId']);
    }
}
