<?php

namespace App\Controller;

use App\Service\CalendarService;
use App\Service\DataService;
use App\Service\ConfigurationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TidalForecastController extends AbstractController
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
        $this->configurationService->setLocationPath();
    }

    #[Route('/forecast/tidal/{date}')]
    public function list($date): Response
    {
        $locations = $this->configurationService->getLocations();
        return $this->render('forecast/tidal-list.html.twig', [
            'date' => $date,
            'year' => $this->calendarService->extractYear($date),
            'month' => $this->calendarService->extractMonth($date),
            'locations' => $locations,
        ]);
    }

    #[Route('/forecast/tidal/{date}/{location}')]
    public function location($date, $location): Response
    {
        if ($date == 'latest') {
            $date = $this->calendarService->getToday();
        }
        $filters = ['highwater','lowwater','gateopen','gateclose'];

        $files = $this->dataService->getFiles($date, $location, $filters);

        if (count($files) > 0 ) {
            foreach($files as $file) {
                $events[] = $this->dataService->getJsonFile($date, $file);
            }

            return $this->render('forecast/tidal-location.html.twig', [
                'events' => $events,
                'location' => $location,
                'year' => $this->calendarService->extractYear($date),
                'month' => $this->calendarService->extractMonth($date),
                'date' => $date
            ]);
        } else {
            return $this->render('system/error.html.twig');
        }


    }

}