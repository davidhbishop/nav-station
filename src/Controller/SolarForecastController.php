<?php

namespace App\Controller;

use App\Service\DateCalculator;
use App\Service\DataService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SolarForecastController extends AbstractController
{
    private DateCalculator $dateCalculator;
    private DataService $dataService;

    public function __construct(DateCalculator $dateCalculator, DataService $dataService){
        $dataPath = __DIR__.'/../../data/forecast';
        $this->dataService = $dataService;
        $this->dateCalculator = $dateCalculator;

        $this->dataService->setDataPath($dataPath);

    }

    #[Route('/forecast/solar/{date}/{location}')]
    public function forecast($date, $location): Response
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 2, 2);
        if ($date == 'latest') {
            $date = $this->dateCalculator->getToday();
        }
        $filters = ['sunset','sunrise','moonrise','moonset'];

        $files = $this->dataService->getFiles($date, $location, $filters);

        if (count($files) > 0 ) {
            foreach($files as $file) {
                $events[] = $this->dataService->getJsonFile($date, $file);
            }

            return $this->render('forecast/solar.html.twig', [
                'events' => $events,
                'location' => $location,
                'date' => $date,
                'year' => $year,
                'month' => $month
            ]);
        } else {
            return $this->render('system/error.html.twig');
        }


    }

}