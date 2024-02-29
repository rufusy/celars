<?php

/**
 * @author Rufusy Idachi
 * @email idachirufus@gmail.com
 * @create date 24-09-2021 23:39:38 
 * @modify date 24-09-2021 23:39:38 
 * @desc
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * @param string $title
     * @return string
     */
    protected function createPageTitle(string $title): string
    {
        return Yii::$app->params['sitename'] . ' | ' . $title;
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function setFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->setFlash('create', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function addFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->addFlash('create', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }
}
