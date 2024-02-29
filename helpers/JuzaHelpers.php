<?php

namespace app\helpers;

use app\models\Subscription;
use app\models\User;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;

class JuzaHelpers
{
    /**
     * Get the format used for dates
     * @return string
     */
    public static function getDateFormat(): string
    {
        return Yii::$app->components['formatter']['dateFormat'];
    }

    /**
     * Get the format used for dates and time
     * @return string
     */
    public static function getDateTimeFormat(): string
    {
        return Yii::$app->components['formatter']['datetimeFormat'];
    }

    /**
     * Get the format used for dates and time
     * @return string
     */
    public static function getTimezone(): string
    {
        return Yii::$app->components['formatter']['defaultTimeZone'];
    }

    /**
     * Format date and/or time into various formats
     * @throws Exception
     */
    public static function formatDate(string $dateToFormat, string $format): string
    {
        $newDate = new DateTime($dateToFormat, new DateTimeZone(self::getTimezone()));
        return $newDate->format($format);
    }

    /**
     * Send an email message
     *
     * @param array $emails content to be passed in the message body
     * @param string $layout Layout of the email message
     * @param string $view body of the email message
     *
     * @throws Exception if email not sent
     *
     * @return void
     */
    public static function sendEmails(array $emails, string $layout, string $view):void
    {
        foreach($emails as $email){
            if(!empty($email['recipientEmail'])){
                $message = Yii::$app->mailer->compose();

                $recipientEmail = $email['recipientEmail'];
                if(YII_ENV_DEV){
                    $recipientEmail = Yii::$app->params['noReplyEmail'];
                }

                $message->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['sitename']])
                    ->setTo($recipientEmail)
                    ->setSubject($email['subject']);

                $body = Yii::$app->mailer->render($view, $email['params'], $layout);
                $message->setHtmlBody($body);
                if(!$message->send()){
                    throw  new Exception('Email not sent.');
                }
            }
        }
    }

    /**
     * @param array $modelErrors
     * @return string
     */
    public static function getModelErrors(array $modelErrors): string
    {
        $errorMsg = '';
        foreach ($modelErrors as $attributeErrors){
            for($i=0; $i < count($attributeErrors); $i++){
                $errorMsg .= ' ' . $attributeErrors[$i];
            }
        }
        return $errorMsg;
    }

    /**
     * @param string $string
     * @param string $startString
     * @return bool
     */
    public static function startsWith (string $string, string $startString): bool
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    /**
     * Check if user has an active subscription
     * @throws Exception
     */
    public static function subscriptionExpired($userId): bool
    {
        $subscription = Subscription::find()->where(['userId' => $userId])->one();
        if(is_null($subscription)){
            return true;
        }
        $endDate = $subscription->enddate;

        $user = User::find()->select(['timezone'])->where(['id' => $userId])->asArray()->one();

        $todayTimeStamp = time();
        $todayDate = new DateTime("now", new DateTimeZone($user['timezone'])); //first argument "must" be a string
        $todayDate->setTimestamp($todayTimeStamp); //adjust the object to correct timestamp
        $todayDate->format('Y-m-d H:i:s');

        $endDateStamp = strtotime($endDate, $todayDate->getTimestamp());
        $endDate = new DateTime("now", new DateTimeZone($user['timezone']));
        $endDate->setTimestamp($endDateStamp);
        $endDate->format('Y-m-d H:i:s');

        if($todayDate->getTimestamp() > $endDate->getTimestamp()){
           return true;
        }

        return false;
    }
}