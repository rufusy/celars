<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\User $profile
 */

use yii\helpers\Url;

$this->title = $title;
?>

<div class="content-header">
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-6 offset-md-3 offset-lg-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title" style="font-weight: 700;">
                            Change password
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="update-password-form" action="<?=Url::to(['/profile/change-password']);?>" method="post"
                              novalidate class="form-horizontal needs-validation">

                            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                            <input type="hidden" id="password-id" name="password-id" value="<?=$profile['id']?>">

                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="old-password" class="col-sm-5 col-form-label required-control-label">Enter old password</label>
                                    <div class="col-sm-7">
                                        <input type="password" minlength="8" class="form-control" id="old-password" name="old-password" required>
                                        <div class="invalid-feedback">
                                            Please provide password
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="new-password" class="col-sm-5 col-form-label required-control-label">New password</label>
                                    <div class="col-sm-7">
                                        <input type="password" minlength="8" class="form-control" id="new-password" name="new-password" required>
                                        <span class="help-block" style="color: dimgrey"> Not less than 8 characters</span>
                                        <div class="invalid-feedback">
                                            Please provide password
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="confirm-password" class="col-sm-5 col-form-label required-control-label">Confirm new password</label>
                                    <div class="col-sm-7">
                                        <input type="password" minlength="8" class="form-control" id="confirm-password" name="confirm-password" required>
                                        <span class="help-block" style="color: dimgrey"> Not less than 8 characters</span>
                                        <div class="invalid-feedback">
                                            Please provide password
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="offset-sm-5 col-sm-7">
                                        <button type="submit" id="btn-update-password" class="btn btn-success">Change Password</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>

            <script>
                // Example starter JavaScript for disabling form submissions if there are invalid fields
                (function() {
                    'use strict';
                    window.addEventListener('load', function() {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.getElementsByClassName('needs-validation');
                        // Loop over them and prevent submission
                        var validation = Array.prototype.filter.call(forms, function(form) {
                            form.addEventListener('submit', function(event) {
                                if (form.checkValidity() === false) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                form.classList.add('was-validated');
                            }, false);
                        });
                    }, false);
                })();
            </script>

        </div>
    </div>
</section>


