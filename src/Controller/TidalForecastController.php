<?php

namespace App\Controller;

use App\Service\DateCalculator;
use App\Service\DataService;
use App\Service\LocationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TidalForecastController extends AbstractController
{
    private DateCalculator $dateCalculator;
    private DataService $dataService;
    private LocationService $locationService;

    public function __construct(DateCalculator $dateCalculator, DataService $dataService, LocationService $locationSevice){
        $dataPath = __DIR__.'/../../data/forecast';
        $this->dataService = $dataService;
        $this->dataService->setDataPath($dataPath);

        $this->dateCalculator = $dateCalculator;

        $this->locationService = $locationSevice;
        $this->locationService->setLocationPath(__DIR__.'/../../data/locations/ship-locations.json');


    }

    #[Route('/forecast/tidal/{date}')]
    public function list($date): Response
    {
        $locations = $this->locationService->getLocations();
        return $this->render('forecast/tidal-list.html.twig', [
            'date' => $date,
            'year' => $this->dateCalculator->extractYear($date),
            'month' => $this->dateCalculator->extractMonth($date),
            'locations' => $locations,
        ]);
    }

    #[Route('/forecast/tidal/{date}/{location}')]
    public function location($date, $location): Response
    {
        if ($date == 'latest') {
            $date = $this->dateCalculator->getToday();
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
                'year' => $this->dateCalculator->extractYear($date),
                'month' => $this->dateCalculator->extractMonth($date),
                'date' => $date
            ]);
        } else {
            return $this->render('system/error.html.twig');
        }


    }

}