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

    #[Route('/forecast/inshore/{date}')]
    public function list($date): Response
    {

        $filters = ['forecast'];
        $year = $this->dateCalculator->extractYear($date);
        $month = $this->dateCalculator->extractMonth($date);

        $files = $this->dataService->getFiles($date, 'inshore', $filters);

        if (count($files) > 0 ) {
            foreach($files as $file) {
                $file_key = $file;
                $file_key = str_replace('0000-inshore-forecast-','',$file_key);
                $file_key = str_replace('.json','',$file_key);
                $forecasts[$file_key] = $this->dataService->getJsonFile($date, $file);
            }

            ksort($forecasts);

            return $this->render('forecast/inshore-list.html.twig',
                [
                    'date' => $date,
                    'forecasts' => $forecasts,
                    'year' => $year,
                    'month' => $month,
                ]);
        } else {
            return $this->render('system/error.html.twig');
        }

    }

    #[Route('/forecast/inshore/{date}/{area}')]
    public function area($date, $area): Response
    {
        $year = $this->dateCalculator->extractYear($date);
        $month = $this->dateCalculator->extractMonth($date);
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
                return $this->render('forecast/inshore-area.html.twig', [
                    'forecast' => $forecast,
                    'date' => $date,
                    'year' => $year,
                    'month' => $month,
                ]);
        } else {
            return $this->render('system/error.html.twig');
        }


    }


}