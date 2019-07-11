<?php

namespace App\Controller;

use App\Service\ApiParameters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherForecastController extends AbstractController
{
    /**
     * @Route("/weather/forecast", name="weather_forecast")
     *
     * @param ApiParameters $api
     *
     * @return Response
     * @throws \Exception
     */
    public function index(ApiParameters $api): Response
    {

        $resultObject = $api->callApi();
        $api = $api->getApiAndCityInfo();

        //$forecast['currently'] = $resultObject['currently'];

        return $this->render('weather_forecast/index.html.twig', [
            'api' => $api,
            'daily' => $resultObject['daily']['data']
        ]);
    }
}
