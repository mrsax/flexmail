<?php

namespace App\Controller;

use App\Entity\Api;
use App\Service\ApiParameters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class WeatherHistoryController extends AbstractController
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
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(ApiParameters $api): Response
    {

        $results = $api->callApiHistory();

        $api = $api->getApiAndCityInfo();

        return $this->render('weather_history/index.html.twig', [
            'data' => $results,
            'api' => $api
        ]);
    }
}
