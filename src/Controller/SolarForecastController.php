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

    #[Route('/forecast/solar/{date}/{area}')]
    public function forecast($date, $area): Response
    {
        if ($date == 'latest') {
            $date = $this->dateCalculator->getToday();
        }
        $filters = ['sunset','sunrise','moonrise','moonset'];

        $files = $this->dataService->getFiles($date, $area, $filters);

        if (count($files) > 0 ) {
            foreach($files as $file) {
                $events[] = $this->dataService->getJsonFile($date, $file);
            }

            return $this->render('forecast/solar.html.twig', [
                'events' => $events,
                'area' => $area
            ]);
        } else {
            return $this->render('system/error.html.twig');
        }


    }

}