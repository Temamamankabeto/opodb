<?php

namespace App\Helpers;

class EthiopianDateHelper
{
    /**
     * Convert Gregorian date to Ethiopian date
     * Returns ['year' => int, 'month' => int, 'day' => int]
     */
    public static function gregorianToEthiopian($gYear, $gMonth = 1, $gDay = 1)
    {
        $jd = gregoriantojd($gMonth, $gDay, $gYear);

        // Julian day of Ethiopian epoch
        $ethiopianEpoch = gregoriantojd(8, 29, 8);

        $days = $jd - $ethiopianEpoch;

        $year = (int)(($days) / 365.25) + 1;
        $remainder = $days - floor(365.25 * ($year - 1));

        $month = (int)($remainder / 30) + 1;
        $day = ($remainder % 30) + 1;

        return [
            'year' => $year,
            'month' => $month,
            'day' => $day
        ];
    }

    /**
     * Get current Ethiopian year
     */
    public static function currentEthiopianYear()
    {
        $today = self::gregorianToEthiopian(date('Y'), date('m'), date('d'));
        return $today['year'];
    }
}
