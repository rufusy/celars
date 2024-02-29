<?php

/* @var $this View */
/* @var $content string */

use app\assets\AdminAsset;
use yii\bootstrap4\Html;
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
<body class="hold-transition login-page">
<?php $this->beginBody() ?>

<?= $content ?>

<?php
include_once Yii::getAlias('@views') . '/includes/_growl.php';
$this->endBody()
?>
</body>
</html>
<?php $this->endPage() ?>
