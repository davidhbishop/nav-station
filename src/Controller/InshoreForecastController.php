<?php

namespace App\Controller;

use App\Service\DateCalculator;
use App\Service\DataService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InshoreForecastController extends AbstractController
{
    private DateCalculator $dateCalculator;
    private DataService $dataService;

    public function __construct(DateCalculator $dateCalculator, DataService $dataService){
        $dataPath = __DIR__.'/../../data/forecast';
        $this->dataService = $dataService;
        $this->dateCalculator = $dateCalculator;

        $this->dataService->setDataPath($dataPath);

    }

    #[Route('/forecast/inshore/{date}/{area}')]
    public function forecast($date, $area): Response
    {
        $time = $this->dateCalculator->getTime();
        if ($date == 'latest') {
            $date = $this->dateCalculator->getToday();
        }

        $this->dataService->setData(
            $date,
            $time,
            'inshore-forecast',
            $area
        );

        if ($this->dataService->getFile()) {
                $forecast = $this->dataService->getJSON();
                return $this->render('forecast/inshore.html.twig', [
                    'forecast' => $forecast,
                ]);
        } else {
            return $this->render('system/error.html.twig');
        }


    }


}