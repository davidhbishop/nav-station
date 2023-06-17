<?php

namespace App\Controller;

use App\Service\ConfigurationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LocationController extends AbstractController
{
    private ConfigurationService $configurationService;

    public function __construct(ConfigurationService $configurationService){
        $this->configurationService = $configurationService;
        $this->configurationService->setLocationPath(__DIR__.'/../../data/locations/ship-locations.json');
    }

    #[Route('/locations/{date}')]
    public function locations($date): Response
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 2, 2);
        $locations = $this->configurationService->getLocations();
        return $this->render('locations.html.twig', [
            'date' => $date,
            'year' => $year,
            'month' => $month,
            'locations' => $locations,
        ]);
    }

}