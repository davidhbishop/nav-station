<?php

namespace App\Model;

use App\Service\DataService;
use App\Model\DateModel;

class LocationModel
{
    private DataService $dataService;

    public function __construct(DataService $dataService){
        $dataPath = __DIR__.'/../../data/forecast';
        $this->dataService = $dataService;
        $this->dataService->setDataPath($dataPath);
    }

    public function getTideTable($dates, $location) {
        $days = array();
        foreach ($dates as $date) {
            $days[$date->date] = $this->getTides($date, $location);
        }

        //Calculate tidal range
        $days = $this->addRange($days);

        //Remove low water
        $days = $this->highTides($days);

        //Remove colon from time
        $days = $this->removeColonFromTime($days);

        //Add date values
        $days = $this->addDateFormats($dates, $days);


        return $days;
    }

    private function removeColonFromTime($days) {
        foreach($days as $day => $tides){
            foreach($tides as $time => $tide) {
                    $days[$day][$time]["time"] = str_replace(':','',$days[$day][$time]["time"]);
            }
        }
        return $days;
    }

    private function addDateFormats($dates, $days) {
        foreach ($days as $key => $day) {

            // @var DateModel $date
            $date = $dates[$key];
            $days[$key]['date'] = $date->asArray();
        }
        return $days;
    }

    private function addRange($days) {
        $lastLowWater = 0;
        foreach($days as $day => $tides){
            foreach($tides as $time => $tide) {
                if ($tide["type"]=='LowWater') {
                    $lastLowWater = $tide["depth"];
                }
                if ($tide["type"]=='HighWater') {
                    $days[$day][$time]["range"] = round($tide["depth"] - $lastLowWater);
                }
            }
        }
        return $days;
    }

    private function highTides($days) {
        //Create a new day array for holding high tides
        $highDays = array();

        foreach($days as $day => $tides){

            //Create a new array each day for holding high tides
            $highTides = array();
            foreach($tides as $time => $tide) {

                //If high tide then keep
                if ($tide["type"]=='HighWater') {
                    $tide["depth"]=round($tide["depth"],1);
                    $highTides[$time] = $tide;
                }

            }

            //Add high tides for the day to high days array
            $highDays[$day] = $highTides;
        }
        return $highDays;
    }

    /*
     * getTides()
     *
     * This function will get the tidal times for a given location and date
     *
     * @param String $location
     * @param DateModel $date
     */
    public function getTides(DateModel $date, $location) {
        $tides = array();
        $filters = ['highwater','lowwater'];

        //  @var DateModel $date

        $files = $this->dataService->getFiles($date->date, $location, $filters);
        if (count($files) > 0) {
            foreach($files as $file) {
                $value = $this->dataService->getJsonFile($date->date, $file);
                $time = $value["time"];
                $tides[$time] = $value;
            }
        }

        return $tides;
    }

    public function getTimeTable(DateModel $date, $location)
    {
        $filters = ['highwater', 'lowwater', 'gateopen', 'gateclose', 'sunset', 'sunrise', 'moonrise', 'moonset'];

        $files = $this->dataService->getFiles($date->date, $location, $filters);
        $times = array();

        if (count($files) > 0) {
            foreach ($files as $file) {
                $times[] = $this->dataService->getJsonFile($date->date, $file);
            }

        }
        return $times;
    }

}