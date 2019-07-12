<?php

namespace App\Controller;

use App\Entity\Api;
use App\Service\ApiParameters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherHistoryController extends AbstractController
{
    public function __construct()
    {
        date_default_timezone_set("Europe/Brussels");
    }

    /**
     * @Route("/weather/history", name="weather_history")
     *
     * @param ApiParameters $api
     * @return Response
     *
     * @throws \Exception
     */
    public function index(ApiParameters $api): Response
    {

        $results = $api->callApiHistory(2);

        $api = $api->getApiAndCityInfo();

        return $this->render('weather_history/index.html.twig', [
            'data' => $results,
            'api' => $api
        ]);
    }
}
