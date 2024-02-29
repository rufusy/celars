<?php

/**
 * @author Rufusy Idachi
 * @email idachirufus@gmail.com
 * @create date 18-09-2021 21:39:33 
 * @modify date 18-09-2021 21:39:33 
 * @desc Manage dashboard layout
 */

/* @var $this View */
/* @var $content string */

use app\assets\AdminAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\View;

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web'); ?>/images/logo.png" type="image/x-icon">
    <link rel="icon" href="<?= Yii::getAlias('@web'); ?>/images/logo.png" type="image/x-icon">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['/site/logout']); ?>">
                    <i class="nav-icon fas fa-sign-out" aria-hidden="true"></i>
                    Log out
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <?= $this->render('./sidebar') ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?= $content ?>
    </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="text-center">
            CENTER FOR LAND ACQUISITION AND RESETTLEMENT STUDIES
            <strong>&copy; <?= date('Y') ?></strong>
            All Rights Reserved. University of Nairobi.
        </div>
    </footer>

</div>
<!-- ./wrapper -->

<?php
include_once Yii::getAlias('@views') . '/includes/_growl.php';

$this->endBody()
?>

</body>
</html>
<?php $this->endPage() ?>
