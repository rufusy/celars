<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\base\Model;

/**
 * ForgotPasswordForm is the model behind the forgot password form.
 */
class ForgotPasswordForm extends Model
{
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            ['email', 'required'],
            ['email', 'string'],
            ['email', 'trim'],
            ['email', 'default'],
            ['email', 'email'],

        ];
    }
}