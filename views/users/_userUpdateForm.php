<?php
/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\Role $listOfRoles[]
 * @var app\models\User $user
 */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'update-user-form',
    'action' => Url::to(['/users/update']),
]);
?>
<div class="loader"></div>
<?= $form->field($user, 'id')->hiddenInput()->label(false) ?>
<?=
$form->field($user, 'firstName')
    ->textInput([
        'id' =>'first-name',
        'class' => 'form-control',
        'placeholder' => 'First name',
    ])
    ->label('First name', ['class' => 'required-control-label']);
?>

<?=
$form->field($user, 'lastName')
    ->textInput([
        'id' =>'last-name',
        'class' => 'form-control',
        'placeholder' => 'Last name',
    ])
    ->label('Last name', ['class' => 'required-control-label']);
?>

<?=
$form->field($user, 'email')
    ->textInput([
        'id' =>'email',
        'placeholder' => 'Email address',
        'class' => 'form-control'
    ])
    ->label('Email address', ['class' => 'required-control-label']);
?>

<?=
$form->field($user, 'mobileNumber')
    ->textInput([
        'id' =>'mobile-number',
        'placeholder' => 'Mobile number',
        'class' => 'form-control'
    ])
    ->label('Mobile number');
?>
<button type="submit" id="update-user-btn" class="btn btn-success">Update</button>
<?php ActiveForm::end(); ?>

<?php
$updateUserJs = <<< JS
$('#update-user-btn').click(function(e){
    $('.loader').html(loader);
});
JS;
$this->registerJs($updateUserJs, yii\web\View::POS_READY);

