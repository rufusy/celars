<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "juzasports_media.mpesa_transactions".
 *
 * @property int $id
 * @property string $merchantRequestID
 * @property string $checkoutRequestID
 * @property int $resultCode
 * @property string $resultDesc
 * @property float|null $amount
 * @property string|null $mpesaReceiptNumber
 * @property float|null $balance
 * @property string|null $transactionDate
 * @property string|null $phoneNumber
 * @property User $user
 */
class MpesaTransaction extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'mpesa_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['merchantRequestID', 'checkoutRequestID', 'resultCode', 'resultDesc'], 'required'],
            [['id', 'resultCode'], 'integer'],
            [['resultDesc'], 'string'],
            [['amount', 'balance'], 'number'],
            [['merchantRequestID', 'checkoutRequestID'], 'string', 'max' => 255],
            [['mpesaReceiptNumber'], 'string', 'max' => 45],
            [['transactionDate'], 'string', 'max' => 14],
            [['phoneNumber'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'merchantRequestID' => 'Merchant Request ID',
            'checkoutRequestID' => 'Checkout Request ID',
            'resultCode' => 'Result Code',
            'resultDesc' => 'Result Desc',
            'amount' => 'Amount',
            'mpesaReceiptNumber' => 'Mpesa Receipt Number',
            'balance' => 'balance',
            'transactionDate' => 'Transaction Date',
            'phoneNumber' => 'Phone Number'
        ];
    }
}
