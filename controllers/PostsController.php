<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 3:01 PM
 */

namespace app\controllers;

use app\models\search\PostsSearch;
use app\services\PostService;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

final class PostsController extends BaseController
{
    private PostService $postService;

    public function init()
    {
        parent::init();

        $this->postService = new PostService();
    }

    /**
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
     * @return string
     */
    public function actionIndex(): string
    {
        $postsSearch = new PostsSearch();
        $postsProvider = $postsSearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'title'=> $this->createPageTitle('posts'),
            'postsSearch' => $postsSearch,
            'postsProvider' => $postsProvider
        ]);
    }

    /**
     * @return string
     */
    public function actionCreate(): string
    {
        return $this->render('create', [
            'title'=> $this->createPageTitle('create post'),
        ]);
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function actionStore(): Response
    {
        $post = Yii::$app->request->post();

        if(empty($post['title']) || empty($post['body']) || empty($_FILES['images'])) {
            throw new UnprocessableEntityHttpException('All mandatory fields must be provided.');
        }

        $post = [
            'title' => $post['title'],
            'body' => $post['body'],
            'publishStatus' => $post['publishStatus'],
            'images' => $_FILES['images'],
            'documents' => $_FILES['documents']
        ];

        $this->postService->storeOrUpdate($post);

        $this->setFlash('success', 'Post', 'New post created successfully.');

        return $this->redirect(['/posts']);
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     */
    public function actionEdit(): string
    {
        $get = Yii::$app->request->get();

        if(empty($get['id'])) {
            throw new UnprocessableEntityHttpException('Missing url parameters');
        }

        return $this->render('edit', [
            'title'=> $this->createPageTitle('edit post'),
            'post' => $this->postService->findPostById($get['id'])
        ]);
    }

    /**
     * @return Response
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate(): Response
    {
        $post = Yii::$app->request->post();

        if(empty($post['title']) || empty($post['body'])) {
            throw new UnprocessableEntityHttpException('All mandatory fields must be provided.');
        }

        $post = [
            'id' => $post['id'],
            'title' => $post['title'],
            'body' => $post['body'],
            'publishStatus' => $post['publishStatus'],
            'images' => $_FILES['images'],
            'documents' => $_FILES['documents']
        ];

        $this->postService->storeOrUpdate($post);

        $this->setFlash('success', 'Post', 'Post updated successfully.');

        return $this->redirect(['/posts']);
    }

    /**
     * @return Response
     * @throws \yii\db\Exception
     * @throws ServerErrorHttpException
     */
    public function actionDelete(): Response
    {
        $post = Yii::$app->request->post();

        $this->postService->delete($post['postId']);

        $this->setFlash('success', 'Post', 'Post deleted successfully.');

        return $this->redirect(['/posts']);
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     */
    public function actionViewAttachments(): string
    {
        $get = Yii::$app->request->get();

        if(empty($get['id'])) {
            throw new UnprocessableEntityHttpException('Missing url parameters');
        }

        return $this->render('view-attachments', [
            'title'=> $this->createPageTitle('post attachments'),
            'files' => $this->postService->viewAttachments($get['id']),
            'path' => Yii::getAlias('@attachmentsUrl') . $get['id'],
            'postId' => $get['id'],
            'post' => $this->postService->findPostById($get['id'])
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
     * @throws UnprocessableEntityHttpException
     * @throws Exception
     */
    public function actionDeleteAttachment(): Response
    {
        $post = Yii::$app->request->post();

        if(empty($post['postId']) || empty($post['fileType']) || empty($post['fileName'])) {
            throw new UnprocessableEntityHttpException('Missing url parameters');
        }

        $filePath = Yii::getAlias('@attachmentsUrl') . $post['postId'] . '/' . $post['fileType'] . '/' . $post['fileName'];

        if (!unlink($filePath)) {
            throw new Exception('Can not be delete file due to an error');
        }

        $this->setFlash('success', 'Post Attachments', 'File deleted successfully.');
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}