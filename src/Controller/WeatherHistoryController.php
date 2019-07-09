<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherHistoryController extends AbstractController
{
    /**
     * @Route("/weather/history", name="weather_history")
     */
    public function index()
    {
        return $this->render('weather_history/index.html.twig', [
            'controller_name' => 'WeatherHistoryController',
        ]);
    }
}
