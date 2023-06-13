<?php

namespace App\Controller;

use App\Service\DateCalculator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CalendarController extends AbstractController
{
    private DateCalculator $dateCalculator;

    public function __construct(DateCalculator $dateCalculator){
        $this->dateCalculator = $dateCalculator;
    }

    #[Route('/')]
    public function homepage(): Response
    {
        $year = $this->dateCalculator->getYear();
        $month = $this->dateCalculator->getMonth();
        return $this->render('home.html.twig', [
            'year' => $year,
            'month' => $month
        ]);
    }

    #[Route('/cal/{year}/{month}')]
    public function month($year, $month): Response
    {
        $days = $this->dateCalculator->getMonthDays($year, $month);
        return $this->render('month.html.twig', [
            'days' => $days,
            'year' => $year,
            'month' => $month,
            'calendar' => $this->dateCalculator->getDate($year.$month.'01')
        ]);
    }

    #[Route('/cal/{date}')]
    public function day($date): Response
    {
        $year = $this->dateCalculator->extractYear($date);
        $month = $this->dateCalculator->extractMonth($date);
        return $this->render('day.html.twig', [
            'calendar' => $this->dateCalculator->getDate($date)
        ]);
    }

}