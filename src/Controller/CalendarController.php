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

    #[Route('/calendar/{year}/{month}')]
    public function calendar($year, $month): Response
    {
        $days = $this->dateCalculator->getMonthDays($year, $month);
        return $this->render('calendar.html.twig', [
            'days' => $days,
            'year' => $year,
            'month' => $month
        ]);
    }

}