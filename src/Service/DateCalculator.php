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


}//end class
