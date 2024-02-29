<?php

/**
 * @author Rufusy Idachi
 * @email idachirufus@gmail.com
 * @create date 08-10-2021 22:22:14 
 * @modify date 08-10-2021 22:22:14 
 * @desc sidebar content
 */

use yii\bootstrap4\Html;
use yii\helpers\Url;
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block" style="color:#fff;">
                    <?= Yii::$app->user->identity->firstName . ' ' .Yii::$app->user->identity->lastName; ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= Url::to(['/profile']);?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Account</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['/posts']);?>" class="nav-link">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>Posts</p>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="clear"></div>
        <img src="<?=Yii::getAlias('@web'); ?>/images/logo.png" alt="logo" class="sidebar-logo">
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>