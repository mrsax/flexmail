<?php

namespace App\Controller;

use App\Service\ApiParameters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Intl\Timezones;

class WeatherForecastController extends AbstractController
{
    private const UNSET_FIELDS = ['moonPhase', 'precipIntensityMaxTime','temperatureHighTime', 'temperatureLowTime', 'temperatureLowTime', 'apparentTemperatureHighTime', 'apparentTemperatureLowTime', 'dewPoint', 'windGust', 'windGustTime', 'cloudCover', 'uvIndexTime', 'visibility', 'temperatureMin', 'temperatureMinTime', 'temperatureMax', 'temperatureMaxTime', 'apparentTemperatureMin', 'apparentTemperatureMinTime', 'apparentTemperatureMax', 'apparentTemperatureMaxTime', 'apparentTemperatureHigh', 'apparentTemperatureLow'];


    public function __construct()
    {
        date_default_timezone_set("Europe/Brussels");
    }

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

        $dailyData = $this->transformData($resultObject['daily']['data']);

        return $this->render('weather_forecast/index.html.twig', [
            'api' => $api,
            'daily' => $dailyData
        ]);
    }

    /**
     * Transform & filter data for layout.
     *
     * @param $resultObject
     *
     * @return array
     */
    private function transformData(array $resultObject): array
    {
        foreach($resultObject as $key => &$value)
        {
            foreach(self::UNSET_FIELDS as $unsetvalue)
            {
                if(array_key_exists($unsetvalue, $value))
                {
                    unset($value[$unsetvalue]);
                }
            }
        }

        return $resultObject;
    }
}
