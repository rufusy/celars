<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 3:01 PM
 */

namespace app\controllers;

use app\models\search\PostsSearch;
use Yii;
use yii\filters\AccessControl;

final class PostsController extends BaseController
{
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

    public function actionStore()
    {
        $post = Yii::$app->request->post();

        $title = $post['title'];
        $body = $post['body'];

//        if(empty($title) || empty($body)) {
//        }

        dd($post);
    }
}