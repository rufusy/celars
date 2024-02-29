<?php
/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\Role $listOfRoles[]
 * @var app\models\User $user
 */

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'new-user-form',
    'action' => Url::to(['/users/store']),
]);
?>
<div class="loader"></div>
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
<div class="form-group">
    <?php
    echo '<label>Roles</label>';
    try {
        echo Select2::widget([
            'id' => 'user-roles',
            'class' => 'form-control',
            'name' => 'roles',
            'data' => $listOfRoles,
            'options' => [
                'placeholder' => 'Roles',
                'multiple' => true
            ],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    ?>
</div>
<button type="submit" id="create-user-btn" class="btn btn-success">Save</button>
<?php ActiveForm::end(); ?>

<?php
$createUserJs = <<< JS
$('#create-user-btn').click(function(e){
    $('.loader').html(loader);
});
JS;
$this->registerJs($createUserJs, yii\web\View::POS_READY);
