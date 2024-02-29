<?php

/**
 * @author Rufusy Idachi
 * @email idachirufus@gmail.com
 * @create date 24-09-2021 23:39:38 
 * @modify date 24-09-2021 23:39:38 
 * @desc Manage users 
 */

namespace app\controllers;

use app\helpers\JuzaHelpers;
use app\models\Role;
use app\models\RoleUser;
use app\models\search\UsersSearch;
use app\models\Status;
use app\models\Team;
use app\models\User;
use app\traits\StatusTrait;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class UsersController extends BaseController
{
    use StatusTrait;

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
     * List users
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionIndex(): string
    {
        try{
            $userSearchModel = new UsersSearch();
            $usersDataProvider = $userSearchModel->search(Yii::$app->request->queryParams);
            $status = Status::find()->select(['id', 'name'])
                ->where(['not', ['in', 'name', ['not_played', 'played']]])
                ->all();

            $statusList =  ArrayHelper::map($status, 'id', function ($state){
                return str_replace('_', ' ', $state->name);
            });

            return $this->render('index', [
                'title'=> $this->createPageTitle('Users'),
                'userSearchModel' => $userSearchModel,
                'usersDataProvider' => $usersDataProvider,
                'listOfStatus' => $statusList
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
     * Display create new user page
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionCreate(): string
    {
        try {
            $roles = Role::find()->select(['id','name'])->all();
            $listOfRoles = ArrayHelper::map($roles, 'id', function($role){
                return $role->name;
            });
            return $this->renderAjax('_userCreateForm', [
                'title' => 'Create user',
                'user' => new user(),
                'listOfRoles' => $listOfRoles
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Save new user
     * @return Response
     * @throws ServerErrorHttpException
     */
    public function actionStore(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
             $post = Yii::$app->request->post();
             $user = new User();
             $user = $this->storeUpdate($user, $post);
             $user->statusId = $this->activeStatus();
            $user->emailVerifiedAt = JuzaHelpers::formatDate('now', 'Y-m-d');
            if($user->save()) {
                 if (!empty($post['roles'])) {
                     foreach ($post['roles'] as $role) {
                         $roleUser = new RoleUser();
                         $roleUser->roleId = $role;
                         $roleUser->userId = $user->id;
                         if(!$roleUser->save()){
                             if(!$roleUser->validate()){
                                 $transaction->rollBack();
                                 $this->setFlash('danger', 'Create user role', JuzaHelpers::getModelErrors($roleUser->getErrors()));
                                 return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
                             }else{
                                 throw new Exception('Failed to create user role.');
                             }
                         }
                     }
                 }
             }else{
                 if(!$user->validate()){
                     $transaction->rollBack();
                     $this->setFlash('danger', 'Create user', JuzaHelpers::getModelErrors($user->getErrors()));
                     return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
                 }else{
                     throw new Exception('Failed to create user.');
                 }
             }
             $transaction->commit();
             $this->setFlash('success', 'Create user', 'User created successfully.');
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
     * Display update user page
     * @param string $id
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionEdit(string $id): string
    {
        try{
            if(empty($id)){
                throw new Exception('User id must be provided.');
            }
            return $this->renderAjax('_userUpdateForm', [
                'title' => 'Update user',
                'user' => $this->getUser($id)
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
     * Update user
     * @throws ServerErrorHttpException
     */
    public function actionUpdate(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $user = $this->getUser($post['User']['id']);
            $user = $this->storeUpdate($user, $post);
            if(!$user->save()){
                throw new Exception('Failed to update user.');
            }
            $transaction->commit();
            $this->setFlash('success', 'Update user', 'User updated successfully.');
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
     * Change model status
     * @return void
     */
    public function actionChangeStatus()
    {
        $this->changeStatus(User::class);
    }

    /**
     * @param User $user
     * @param array $post
     * @return User
     * @throws Exception
     */
    private function storeUpdate(User $user, array $post): User
    {
        $user->firstName = $post['User']['firstName'];
        $user->lastName = $post['User']['lastName'];
        $user->email = $post['User']['email'];
        // @todo limit the mobile number length to 15 if provided
        $user->mobileNumber = $post['User']['mobileNumber'];
        if(is_null($user->id)){
            $user->beforeSave(true);

            // @todo generate random password and send via email.
            $password = 'password';
            try{
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
            }catch(Exception $ex){
                $message = 'Bad password';
                if(YII_ENV_DEV){
                    $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                }
                throw new Exception($message);
            }
        }else{
            $user->beforeSave(false);
        }
        return $user;
    }

    /**
     * Get user
     *
     * @param $id
     * @return User
     * @throws Exception
     */
    private function getUser($id): User
    {
        $user = User::findOne($id);
        if(is_null($user)){
            throw new Exception('User not found.');
        }
        return $user;
    }
}
