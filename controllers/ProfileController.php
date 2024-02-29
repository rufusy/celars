<?php

namespace app\controllers;

use app\helpers\JuzaHelpers;
use app\models\User;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class ProfileController extends BaseController
{
    /**
     * Configure controller behaviours
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $id
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionEdit(string $id): string
    {
        try{
            if(empty($id)){
                throw new Exception('Missing user in the edit url.');
            }

            if(Yii::$app->user->identity->id !== intval($id)){
                throw new Exception('Wrongly configured user edit url.');
            }

            $this->layout = 'siteMainMinimal';

            return $this->render('edit', [
                'title'=> $this->createPageTitle('My profile'),
                'profile' => User::find()->where(['id' => $id])->asArray()->one(),
            ]);
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionUpdate(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $user = User::findOne($post['id']);
            $user->firstName = $post['first-name'];
            $user->lastName = $post['last-name'];
            $user->email = $post['email'];
            $user->mobileNumber = $post['mobile-number'];

            if(strlen($post['mobile-number']) !== 12){
                $this->setFlash('danger', 'Profile update', 'This mobile number must be 12 digits long');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if(!JuzaHelpers::startsWith($post['mobile-number'], '254')){
                $this->setFlash('danger', 'Profile update', 'This mobile number must start with 254');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            $otherUser = User::find()->where(['mobileNumber' => $post['mobile-number']])
                ->andWhere(['not', ['id' => $post['id']]])->count();

            if($otherUser > 0){
                $this->setFlash('danger', 'Profile update', 'This mobile number is already taken.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if(!$user->save()){
                if(!$user->validate()){
                    $message = JuzaHelpers::getModelErrors($user->getErrors());
                    $transaction->rollBack();
                    $this->setFlash('danger', 'Profile update', $message);
                    return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
                }
                throw new Exception('Failed to update user profile.');
            }

            $transaction->commit();
            $this->setFlash('success', 'Profile update', 'Profile updated successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch(Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionChangePassword(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $user = User::findOne($post['password-id']);

            $oldPassword = $post['old-password'];
            $newPassword = $post['new-password'];
            $confirmPassword = $post['confirm-password'];

            if($newPassword !== $confirmPassword){
                $this->setFlash('danger', 'Password change', 'New and confirm passwords must match.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if(strlen($newPassword) < 8){
                $this->setFlash('danger', 'Password change', 'Password must be at least 8 characters long.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if($user->validatePassword($oldPassword)){
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($newPassword);
            }else{
                $this->setFlash('danger', 'Password change', 'Incorrect old password.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if(!$user->save()){
                throw new Exception('Failed to change user password.');
            }

            $transaction->commit();
            $this->setFlash('success', 'Password change', 'Password changed successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch(Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
}