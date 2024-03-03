<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 12:22 PM
 */

/**
 * @var string $content
 */

use yii\bootstrap4\Html;

\app\assets\BlogAsset::register($this);
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
    <style>.ie-panel{display: none;background: #212121;padding: 10px 0;box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3);clear: both;text-align:center;position: relative;z-index: 1;} html.ie-10 .ie-panel, html.lt-ie-10 .ie-panel {display: block;}</style>
    <?php $this->head() ?>
</head>

<?php $this->beginBody() ?>

<div class="ie-panel"><a href="http://windows.microsoft.com/en-US/internet-explorer/"><img src="images/ie8-panel/warning_bar_0000_us.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a></div>
<!--<div class="preloader">-->
<!--    <div class="preloader-logo"><img src="--><?//= Yii::getAlias('@web'); ?><!--/images/logo.png" alt="" width="151" height="44" srcset="--><?//= Yii::getAlias('@web'); ?><!--/images/logo.png 2x"/>-->
<!--    </div>-->
<!--    <div class="preloader-body">-->
<!--        <div id="loadingProgressG">-->
<!--            <div class="loadingProgressG" id="loadingProgressG_1"></div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="page">
    <header class="section novi-background page-header">
        <!-- https://www.codeply.com/go/kTGlK9Axdk -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="<?=\yii\helpers\Url::to(['/site'])?>">
                    <img src="<?= Yii::getAlias('@web'); ?>/images/logo.png" width="50" height="50" class="d-inline-block align-top" alt="">
                </a>
                <button class="navbar-toggler float-right" type="button" data-toggle="collapse" data-target="#navbar9">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse" id="navbar9">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">About us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Breadcrumbs -->
    <section class="section novi-background breadcrumbs-custom bg-image context-dark"
             style="background-image: url(https://www.clc.gov.sg/images/default-source/homepage/banner/banner.jpg?sfvrsn=9faa711f_2);">
        <div class="breadcrumbs-custom-inner">
            <div class="container breadcrumbs-custom-container">
                <div class="breadcrumbs-custom-main">
                    <h2 class="text-uppercase breadcrumbs-custom-title">CENTER FOR LAND ACQUISITION AND RESETTLEMENT STUDIES</h2>
                </div>
            </div>
        </div>
    </section>

    <?= $content ?>

    <!-- Page Footer-->
    <footer class="footer section novi-background footer-advanced bg-gray-700">
        <div class="footer-advanced-aside">
            <div class="container">
                <div class="footer-advanced-layout">
                    <div>
                        <ul class="list-nav">
                            <li>
                                Contact us on
                                <a href="mailto:<?=Yii::$app->params['infoEmail']?>">
                                    <?=strtolower(Yii::$app->params['infoEmail'])?>
                                </a>

                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">
            <hr>
        </div>
        <div class="footer-advanced-aside">
            <div class="container">
                <div class="footer-advanced-layout">
                    <p class="brand">CENTER FOR LAND ACQUISITION AND RESETTLEMENT STUDIES</p>
                    <p><span>&copy;&nbsp;</span><span class="copyright-year"></span>. All Rights Reserved. University of Nairobi.</p>
                </div>
            </div>
        </div>
    </footer>
</div>
<?php
$this->endBody();
$this->endPage();
?>



