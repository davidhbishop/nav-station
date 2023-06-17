<?php

namespace App\Controller;

use App\Service\CalendarService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CalendarController extends AbstractController
{
    private CalendarService $calendarService;

    public function __construct(CalendarService $calendarService){
        $this->calendarService = $calendarService;
    }

    #[Route('/')]
    public function homepage(): Response
    {
        $year = $this->calendarService->getYear();
        $month = $this->calendarService->getMonth();
        return $this->render('home.html.twig', [
            'year' => $year,
            'month' => $month
        ]);
    }

    #[Route('/cal/{year}/{month}')]
    public function month($year, $month): Response
    {
        $days = $this->calendarService->getMonthDays($year, $month);
        return $this->render('month.html.twig', [
            'days' => $days,
            'year' => $year,
            'month' => $month,
            'calendar' => $this->calendarService->getDate($year.$month.'01')
        ]);
    }

    #[Route('/cal/{date}')]
    public function day($date): Response
    {
        $year = $this->calendarService->extractYear($date);
        $month = $this->calendarService->extractMonth($date);
        return $this->render('day.html.twig', [
            'calendar' => $this->calendarService->getDate($date)
        ]);
    }

}