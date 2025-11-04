<?php

namespace App\Helpers;

class EthiopianYear
{
    /**
     * Get current Ethiopian year
     */
    public static function now()
    {
        $gregorianYear = date('Y');
        $gregorianMonth = date('n');
        $gregorianDay = date('j');

        // Ethiopian New Year starts on September 11 (or 12 in leap years)
        if ($gregorianMonth < 9 || ($gregorianMonth == 9 && $gregorianDay < 11)) {
            return $gregorianYear - 8;
        } else {
            return $gregorianYear - 7;
        }
    }

    /**
     * Convert Gregorian date to Ethiopian date
     */
    public static function gregorianToEthiopian($year, $month, $day)
    {
        $jd = self::gregorianToJD($year, $month, $day);
        return self::jdToEthiopian($jd);
    }

    /**
     * Convert Ethiopian date to Gregorian date
     */
    public static function ethiopianToGregorian($year, $month, $day)
    {
        $jd = self::ethiopianToJD($year, $month, $day);
        return self::jdToGregorian($jd);
    }

    /**
     * Gregorian to Julian Day
     */
    private static function gregorianToJD($year, $month, $day)
    {
        if ($month < 3) {
            $year--;
            $month += 12;
        }

        $a = (int)($year / 100);
        $b = (int)($a / 4);
        $c = 2 - $a + $b;
        $d = (int)(365.25 * ($year + 4716));
        $e = (int)(30.6001 * ($month + 1));

        return $c + $d + $e + $day - 1524.5;
    }

    /**
     * Julian Day to Gregorian
     */
    private static function jdToGregorian($jd)
    {
        $jd += 0.5;
        $z = (int)$jd;
        $f = $jd - $z;

        if ($z < 2299161) {
            $a = $z;
        } else {
            $alpha = (int)(($z - 1867216.25) / 36524.25);
            $a = $z + 1 + $alpha - (int)($alpha / 4);
        }

        $b = $a + 1524;
        $c = (int)(($b - 122.1) / 365.25);
        $d = (int)(365.25 * $c);
        $e = (int)(($b - $d) / 30.6001);

        $day = $b - $d - (int)(30.6001 * $e) + $f;
        $month = ($e < 14) ? $e - 1 : $e - 13;
        $year = ($month > 2) ? $c - 4716 : $c - 4715;

        return [
            'year' => $year,
            'month' => $month,
            'day' => (int)$day
        ];
    }

    /**
     * Ethiopian to Julian Day
     */
    private static function ethiopianToJD($year, $month, $day)
    {
        $jd = self::gregorianToJD($year + 8, 9, 11);
        return $jd + 365 * ($year - 1) + (int)($year / 4) + 30 * ($month - 1) + $day - 31;
    }

    /**
     * Julian Day to Ethiopian
     */
    private static function jdToEthiopian($jd)
    {
        $gregorian = self::jdToGregorian($jd);
        $ethiopianEpoch = self::gregorianToJD(8, 9, 11);

        $diff = $jd - $ethiopianEpoch;
        $year = (int)(($diff + 365) / 1461) * 4 + (int)(($diff % 1461) / 365);
        $month = (int)(($diff % 365) / 30) + 1;
        $day = ($diff % 365) % 30 + 1;

        return [
            'year' => $year + 1,
            'month' => $month,
            'day' => $day
        ];
    }

    /**
     * Get Ethiopian month name
     */
    public static function getMonthName($month)
    {
        $months = [
            1 => 'Meskerem',
            2 => 'Tikimit',
            3 => 'Hidar',
            4 => 'Tahesas',
            5 => 'Tir',
            6 => 'Yekatit',
            7 => 'Megabit',
            8 => 'Miazia',
            9 => 'Genbot',
            10 => 'Sene',
            11 => 'Hamle',
            12 => 'Nehase',
            13 => 'Pagume'
        ];

        return $months[$month] ?? '';
    }

    /**
     * Get Ethiopian day name
     */
    public static function getDayName($day)
    {
        $days = ['Ehud', 'Segno', 'Maksegno', 'Irob', 'Hamus', 'Arb', 'Kidame'];
        return $days[($day - 1) % 7] ?? '';
    }
}
