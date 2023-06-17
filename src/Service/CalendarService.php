<?php

namespace App\Service;

use App\Model\DateModel;

/**
 * Calendar Service
 *
 * Has a function to get the current day in YYMMDD format.
 */
class CalendarService
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
        $date = date_create();
        return $this->returnDate($date);

    }//end getToday()

    /*
     * Function to generate an array of days
     */
    public function getWeekAsDays()
    {
        //Array to hold the return of dates
        $output = array();

        //Get the current date
        $date = date_create();

        //Pluck the day as as string
        $day = date_format($date,'d');

        //Pluck the month as a string
        $month = date_format($date, 'm');

        //Pluck the year as a string
        $year = date_format($date, 'Y');

        //Find out how many days there are in the current month
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        //How may days are left this month
        $daysLeft = $days-$day;

        //If the days left this month is more than seven we can do a simple calculation
        if ($daysLeft > 7) {

            for ($i = $day; $i <= ($day+7); $i++) {

                //Add an array of useful values for the current day
                $output[] = $this->generateDate($year, $month, $i);

            }

        } else {

        }
        return $output;

    }

    /*
     * generateDate()
     *
     * This function will return an array of useful formats for a given day
     */
    private function generateDate($year, $month, $day) {
        $mktime = mktime(0, 0, 0, $month, $day, $year);
        $date = date("Ymd", $mktime);
        return $this->returnDate(date_create($date));

    }


    //todo: Refactor to remove
    public function getYear()
    {
        $currentDay = date_create();
        return date_format($currentDay,'Y');
    }

    //todo: refactor to remove
    public function getMonth(){
        $currentDay = date_create();
        return date_format($currentDay, 'm');
    }

    public function extractYear($date) {
        return substr($date, 0, 4);
    }

    public function extractMonth($date) {
        return substr($date, 4, 2);
    }

    public function getDate($dateString) {
        $date = date_create($dateString);
        return $this->dateArray($date);
    }

    private function returnDate($date) {
        return new DateModel($date);
        /*
        return [
            'year' => date_format($date, 'Y'),
            'month' => date_format($date, 'm'),
            'day' => date_format($date, 'd'),
            'date' => date_format($date,'Ymd'),
            'dayOfWeek' => date_format($date, 'D'),
            'monthOfYear' => date_format($date, 'M'),
            'dayOfMonth' => date_format($date, 'jS')
        ];*/
    }


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
