<?php

namespace App\Controller;

use App\Model\InshoreForecastModel;
use App\Model\PressureForecastModel;
use App\Model\WindForecastModel;

use App\Service\CalendarService;
use App\Service\ConfigurationService;
use App\Service\PreferenceService;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForecastController extends AbstractController
{
    private CalendarService $calendarService;
    private ConfigurationService $configurationService;
    private PreferenceService $preferenceService;

    private InshoreForecastModel $inshoreForecastModel;
    private PressureForecastModel $pressureForecastModel;
    private WindForecastModel $windForecastModel;

    public function __construct(CalendarService $calendarService,
                                ConfigurationService $configurationService,
                                PreferenceService $preferenceService,
                                InshoreForecastModel $InshoreForecastModel,
                                PressureForecastModel $pressureForecastModel,
                                WindForecastModel $windForecastModel) {
        $this->calendarService = $calendarService;
        $this->configurationService = $configurationService;
        $this->preferenceService = $preferenceService;
        $this->inshoreForecastModel = $inshoreForecastModel;
        $this->pressureForecastModel = $pressureForecastModel;
        $this->windForecastModel = $windForecastModel;
    }

    #[Route('/forecast/inshore')]
    public function inshore(): Response {
        $area = $this->preferenceService->getArea();

        if (!$area) {
            return $this->changeArea();
        }

        //Get today
        $date = $this->calendarService->getToday();

        //Get the inshore forecast
        $forecast = $this->inshoreForecastModel->getInshoreForecast($date, $area);

        return $this->render('forecast/inshore.html.twig', [
            'forecast' => $forecast,
            'area' => $area,
            'month' => $dates[array_key_first($dates)]->monthOfYear,
            'year' => $dates[array_key_first($dates)]->year,
        ]);

    }



    #[Route('/change-area')]
    public function changeArea(): Response
    {
        $areas = $this->configurationService->getAreas();
        return $this->render('areas.html.twig', [
            'areas' => $areas
        ]);
    }

    #[Route('/set-area/{area}')]
    public function setArea($area): Response
    {
        $this->preferenceService->setArea($area);
        return $this->inshore();
    }
}
