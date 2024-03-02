<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/2/2024
 * @time: 2:01 PM
 */

namespace app\traits;

use DateTime;
use DateTimeZone;
use Exception;
use Yii;

trait DateTrait
{
    /**
     * Format date and/or time into various formats
     * @throws Exception
     */
    private function formatDate(string $dateToFormat, string $format): string
    {
        $timezone = Yii::$app->components['formatter']['defaultTimeZone'];

        $newDate = new DateTime($dateToFormat, new DateTimeZone($timezone));

        return $newDate->format($format);
    }
}