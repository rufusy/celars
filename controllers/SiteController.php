<?php

namespace app\controllers;

use app\models\LoginForm;
use app\services\PostService;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

final class SiteController extends BaseController
{
    private PostService $postService;

    public function init()
    {
        parent::init();

        $this->layout = 'blogLayout';

        $this->postService = new PostService();
    }

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
        $query = (new Query())
            ->select(['p.id', 'p.title', 'p.created_at', 'p.updated_at', 'p.published_at', 'p.deleted_at'])
            ->from('posts_celars p')
            ->where(['p.deleted_at' => null])
            ->andWhere(['not', ['p.published_at' => null]]);

        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize' => 8]);
        $posts = $query->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('index', [
            'title'=> $this->createPageTitle('home'),
            'posts' => $posts,
            'pagination' => $pagination
        ]);
    }

    /**
     * @throws UnprocessableEntityHttpException
     */
    public function actionPostImages(): Response|\yii\console\Response
    {
        $get = Yii::$app->request->get();

        if(empty($get['postId']) || empty($get['fileName'])) {
            throw new UnprocessableEntityHttpException('Missing url parameters');
        }

        $filePath = Yii::getAlias('@attachmentsUrl') . $get['postId'] . '/img/'. $get['fileName'];

        return Yii::$app->response->sendFile($filePath);
    }

    /**
     * @throws UnprocessableEntityHttpException|NotFoundHttpException
     */
    public function actionView(): string
    {
        $get = Yii::$app->request->get();

        if(empty($get['post'])) {
            throw new UnprocessableEntityHttpException('Missing url parameters');
        }

        return $this->render('view', [
            'title'=> $this->createPageTitle('view post'),
            'post' => $this->postService->findPostById($get['post'])
        ]);
    }

    /**
     * @return Response|\yii\console\Response
     * @throws UnprocessableEntityHttpException
     */
    public function actionDownloadAttachment(): Response|\yii\console\Response
    {
        $get = Yii::$app->request->get();

        if(empty($get['postId']) || empty($get['fileType']) || empty($get['docName'])) {
            throw new UnprocessableEntityHttpException('Missing url parameters');
        }

        $filePath = Yii::getAlias('@attachmentsUrl') . $get['postId'] . '/' . $get['fileType'] . '/' . $get['docName'];

        return Yii::$app->response->sendFile($filePath, $get['docName'], ['inline' => true]);
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
