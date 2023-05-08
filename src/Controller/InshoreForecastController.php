<?php

namespace App\Controller;

use App\Service\DateCalculator;
use App\Service\DataService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InshoreForecastController extends AbstractController
{
    #[Route('/inshore-forecast')]
    public function forecast(): Response
    {
        $dataPath = __DIR__.'/../../data/forecast';
        $dateCalculator = new DateCalculator();
        $area = 10;
        $forecast = '';

        $dataService = new DataService($dataPath);
        $dataService->setData(
            $dateCalculator->getToday(),
            $dateCalculator->getTime(),
            'inshore-forecast',
            $area
        );

        if ($dataService->getFile()) {
            $forecast_string = $dataService->getString();
            $forecast = json_decode($forecast_string, true);
        }

        return $this->render('forecast/inshore.html.twig', [
            'wind' => $forecast['forecast']['wind'],
        ]);

    }


}