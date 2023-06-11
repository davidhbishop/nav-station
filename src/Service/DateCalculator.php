<?php

namespace App\Service;

/**
 * Date calculator
 *
 * Has a function to get the current day in YYMMDD format.
 */
class DateCalculator
{


    /**
     * Get today
     *
     * This function returns the current date in YYMMDD format.
     *
     * @return string
     */
    public function getToday()
    {
        $currentDay = date_create();
        return date_format($currentDay, 'Ymd');

    }//end getToday()


    /**
     * Get time
     *
     * This function returns the time, default to 0000.
     *
     * @return string
     */
    public function getTime()
    {
        return '0000';

    }//end getTime()

    public function getMonthDays($year, $month) {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date("Ymd", $mktime);
            $dates_month[] = ['date'=>$date, 'day'=>$i];
        }

        return $dates_month;
    }

}//end class
