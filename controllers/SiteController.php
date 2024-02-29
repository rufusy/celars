<?php

namespace app\controllers;

use app\models\LoginForm;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['error' => "string[]"])]
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if(parent::beforeAction($action)) {
            if ($action->id == 'error') {
                $this->layout = 'error';
            }
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->layout = 'blogLayout';

        return $this->render('index', [
            'title'=> $this->createPageTitle('home')
        ]);
    }

    /**
     * @return Response|string|\yii\console\Response
     */
    public function actionLogin(): Response|string|\yii\console\Response
    {
        if(Yii::$app->user->isGuest){
            $this->layout = 'login';
            return $this->render('login', [
                'loginForm' => new LoginForm()
            ]);
        }

        return $this->redirect(['/dash/index']);
    }

    /**
     * @return Response
     * @throws ServerErrorHttpException
     */
    public function actionProcessLogin(): Response
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->post());
        if($model->validate()){
            if(Yii::$app->user->login($model->getUser())){
                Yii::$app->getSession()->setFlash('create', [
                    'type' => 'success',
                    'title' => 'Login',
                    'message' => 'Logged in successfully'
                ]);
                return $this->redirect(['/dash/index']);
            }else{
                throw new ServerErrorHttpException('An error occurred while trying to log in.');
            }
        }else{
            Yii::$app->getSession()->setFlash('create', [
                'type' => 'danger',
                'title' => 'Login',
                'message' => 'Incorrect username or password.'
            ]);
            return $this->redirect(['/site/login']);
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
