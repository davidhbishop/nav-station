<?php

namespace App\Controller;

use App\Model\LocationModel;
use App\Service\CalendarService;
use App\Service\ConfigurationService;
use App\Service\PreferenceService;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TideController extends AbstractController
{
    private CalendarService $calendarService;
    private ConfigurationService $configurationService;
    private PreferenceService $preferenceService;
    private LocationModel $locationModel;

    public function __construct(CalendarService $calendarService,
                                ConfigurationService $configurationService,
                                PreferenceService $preferenceService,
                                LocationModel $locationModel) {
        $this->calendarService = $calendarService;
        $this->configurationService = $configurationService;
        $this->preferenceService = $preferenceService;
        $this->locationModel = $locationModel;
    }

    #[Route('/tide-table')]
    public function table(): Response {
        $location = $this->preferenceService->getLocation();

        //If no location then redirect to select location
        if (!$location) {
            return $this->locations();
        }

        //Get the current date
        $dates = $this->calendarService->getWeekAsDays();

        //Get the weeks tide table
        $tides = $this->locationModel->getTideTable($dates, $location);

        return $this->render('forecast/tide-table.html.twig', [
            'tides' => $tides,
            'location' => $location,
            'dates' => $dates
        ]);

    }

    #[Route('/tide-table/{location}/{date}')]
    public function times($location, $date): Repsponse {
        $location = $this->preferenceService->getLocation();

        $date = $this->calendarService->getDate($date);



    }

}