<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 2:46 PM
 */

/* @var $this yii\web\View */
/* @var app\models\LoginForm $loginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <?php
            $form = ActiveForm::begin([
                'id' => 'login-form',
                'action' => Url::to(['/site/process-login']),
            ]);

            echo $form->field($loginForm, 'username')
                ->textInput([
                    'type' => 'email',
                    'class' => 'form-control'
                ])
                ->label('Email', ['class' => 'required-control-label']);

            echo $form->field($loginForm, 'password')
                ->textInput([
                    'type' => 'password',
                    'class' => 'form-control'
                ])
                ->label('Password', ['class' => 'required-control-label']);
            ?>
            <div class="row">
                <div class="col-8"></div>
                <div class="col-4">
                    <button type="submit" class="btn btn-success btn-block">Sign In</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <p class="mb-1">
                <?php
                echo Html::a('I forgot my password', ['/site/forgot-password'], ['title' => 'I forgot my password', 'class' => 'btn-link']);
                ?>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->