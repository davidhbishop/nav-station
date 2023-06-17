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
            $days[] = $this->getTides($date, $location);
        }
        return $days;
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
                $tides[] = $this->dataService->getJsonFile($date->date, $file);
            }
        }
        return $tides;
    }

    public function getTimeTable($date, $location) {
        $filters = ['highwater','lowwater','gateopen','gateclose','sunset','sunrise','moonrise','moonset'];

    }




}