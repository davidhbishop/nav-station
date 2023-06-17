<?php

namespace App\Model;

class DateModel
{
    public string $year;
    public string $month;
    public string $day;
    public string $dayOfWeek;
    public string $monthOfYear;
    public string $dayOfMonth;

    public function __construct($date) {
        $this->year = date_format($date, 'Y');
        $this->month = date_format($date, 'm');
        $this->day = date_format($date, 'd');
        $this->date = date_format($date,'Ymd');
        $this->dayOfWeek = date_format($date, 'D');
        $this->monthOfYear = date_format($date, 'M');
        $this->dayOfMonth = date_format($date, 'jS');
    }

    public function asArray() {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'date' => $this->date,
            'dayOfWeek' => $this->dayOfWeek,
            'monthOfYear' => $this->monthOfYear,
            'dayOfMonth' => $this->dayOfMonth,
        ];
    }

}