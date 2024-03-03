<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/3/2024
 * @time: 3:30 PM
 */

namespace app\controllers;

use app\models\User;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

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
     * @throws ForbiddenHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function actionEdit(string $id): string
    {
        if(empty($id)){
            throw new UnprocessableEntityHttpException('Missing user in the edit url.');
        }

        if(Yii::$app->user->identity->id !== intval($id)){
            throw new ForbiddenHttpException('You can only change passwords for your account.');
        }

        return $this->render('edit', [
            'title'=> $this->createPageTitle('My profile'),
            'profile' => User::find()->where(['id' => $id])->asArray()->one(),
        ]);
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