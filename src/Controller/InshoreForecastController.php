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

    #[Route('/inshore-forecast/{area}')]
    public function forecast($area): Response
    {
        $forecast = '';

        $this->dataService->setData(
            $this->dateCalculator->getToday(),
            $this->dateCalculator->getTime(),
            'inshore-forecast',
            $area
        );

        if ($this->dataService->getFile()) {
            $forecast_string = $this->dataService->getString();
            $forecast = json_decode($forecast_string, true);
        }

        return $this->render('forecast/inshore.html.twig', [
            'forecast' => $forecast,
        ]);

    }


}