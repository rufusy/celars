<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Response;

final class DashController extends BaseController
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
     * @return Response
     */
    public function actionIndex(): Response
    {
        return $this->redirect(['/posts']);
    }
}
