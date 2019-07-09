<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherForecastController extends AbstractController
{
    /**
     * @Route("/weather/forecast", name="weather_forecast")
     */
    public function index(): Response
    {
        return $this->render('weather_forecast/index.html.twig', [
            'controller_name' => 'WeatherForecastController',
        ]);
    }
}
