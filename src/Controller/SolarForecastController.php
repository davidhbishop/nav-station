<?php

namespace App\Controller;

use App\Service\CalendarService;
use App\Service\DataService;
use App\Service\ConfigurationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SolarForecastController extends AbstractController
{
    private CalendarService $calendarService;
    private DataService $dataService;
    private ConfigurationService $configurationService;

    public function __construct(CalendarService $calendarService, DataService $dataService, ConfigurationService $locationSevice){
        $dataPath = __DIR__.'/../../data/forecast';
        $this->dataService = $dataService;
        $this->dataService->setDataPath($dataPath);

        $this->calendarService = $calendarService;

        $this->configurationService = $locationSevice;
        $this->configurationService->setLocationPath(__DIR__.'/../../data/locations/ship-locations.json');
    }

    #[Route('/forecast/solar/{date}')]
    public function list($date): Response
    {
        $locations = $this->configurationService->getLocations();
        return $this->render('forecast/solar-list.html.twig', [
            'date' => $date,
            'year' => $this->calendarService->extractYear($date),
            'month' => $this->calendarService->extractMonth($date),
            'locations' => $locations,
        ]);
    }

    #[Route('/forecast/solar/{date}/{location}')]
    public function forecast($date, $location): Response
    {
        if ($date == 'latest') {
            $date = $this->calendarService->getToday();
        }
        $filters = ['sunset','sunrise','moonrise','moonset'];

        $files = $this->dataService->getFiles($date, $location, $filters);

        if (count($files) > 0 ) {
            foreach($files as $file) {
                $events[] = $this->dataService->getJsonFile($date, $file);
            }

            return $this->render('forecast/solar-location.html.twig', [
                'events' => $events,
                'location' => $location,
                'date' => $date,
                'year' => $this->calendarService->extractYear($date),
                'month' => $this->calendarService->extractMonth($date),
            ]);
        } else {
            return $this->render('system/error.html.twig');
        }


    }

}