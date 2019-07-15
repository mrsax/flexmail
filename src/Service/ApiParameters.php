<?php


namespace App\Service;

use App\Entity\Api;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Darksky\Darksky;
use Darksky\DarkskyException;
use GuzzleHttp;
use GuzzleHttp\Exception as GuzzleException;





/**
 * Service that provides everything about the Api parameters.
 * Class ApiParameters
 * @package App\Service
 */
class ApiParameters
{
    private const UNSET_FIELDS = ['precipIntensity', 'precipIntensityMax', 'moonPhase', 'precipIntensityMaxTime','temperatureHighTime', 'temperatureLowTime', 'temperatureLowTime', 'apparentTemperatureHighTime', 'apparentTemperatureLowTime', 'dewPoint', 'windGust', 'windGustTime', 'cloudCover', 'uvIndexTime', 'visibility', 'temperatureMin', 'temperatureMinTime', 'temperatureMax', 'temperatureMaxTime', 'apparentTemperatureMin', 'apparentTemperatureMinTime', 'apparentTemperatureMax', 'apparentTemperatureMaxTime', 'apparentTemperatureHigh', 'apparentTemperatureLow'];

    private const  API_FORM_INPUT_METHOD = 'apiMethod';
    private const  API_FORM_INPUT_URL = 'apiUrl';
    private const  API_FORM_INPUT_ENDPOINT = 'endpoint';
    private const  API_FORM_INPUT_KEY = 'apiKey';
    private const  API_FORM_INPUT_CITY = 'city';

    private $em;

    /**
     * ApiParameters constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $prams
     *
     * @return Api
     *
     * @throws \Exception
     */
    public function setApiParameters(array $prams): Api
    {

        $apiRepo = $this->em->getRepository(Api::class);
        $cityRepo = $this->em->getRepository(City::class);

        $city = $cityRepo->findOneBy(['name' => $prams[self::API_FORM_INPUT_CITY]->getName()]);

        if($city === null)
        {
            throw new Exception("`{$prams[self::API_FORM_INPUT_CITY]->getName()}` does not exist as a city!");
        }

        $res = $apiRepo->findAll();

        //update or create api settings input
        if (count($res) === 0 )
        {
            $api = new Api();

            $api->setMethod($prams[self::API_FORM_INPUT_METHOD])
                ->setUrl($prams[self::API_FORM_INPUT_URL])
                ->setEndpoint($prams[self::API_FORM_INPUT_ENDPOINT])
                ->setApiKey($prams[self::API_FORM_INPUT_KEY])
                ->setCity($city)
                ->setCreated();

            $this->em->persist($api);
            $this->em->flush($api);
        }
        else
        {
            $res[0]->setMethod($prams[self::API_FORM_INPUT_METHOD])
                ->setUrl($prams[self::API_FORM_INPUT_URL])
                ->setEndpoint($prams[self::API_FORM_INPUT_ENDPOINT])
                ->setApiKey($prams[self::API_FORM_INPUT_KEY])
                ->setCity($city)
                ->setUpdated(new \DateTime("now"));
            $api = $res[0];

        }

        return $api;
    }

    /**
     * Call the weather API service and return a parsed jSon.
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function callApi(): array
    {
        $apiRepo = $this->em->getRepository(Api::class);
        $params = $apiRepo->findAll()[0];
        $exculdedBlocks = ['minutely', 'hourly', 'alerts', 'flags', 'currently'];

        try {

            $result = (new Darksky($params->getApiKey()))->forecast($params->getCity()->getLatitude(), $params->getCity()->getLongitude(), $exculdedBlocks);
            $res = json_decode($result, true);

            if ($res === null) {
                throw new Exception('No data has been returned from API!');
            }

        } catch(DarkskyException $e) {
            throw new DarkskyException('The call to the API failed!');
        } catch(Exception $e) {
            throw new Exception('The call to the API failed!');
        }

        return $res;
    }

    /**
     * Get the info from the current api settings and linked city.
     *
     * @return Api|object
     */
    public function getApiAndCityInfo(): Api
    {
        $apiRepo = $this->em->getRepository(Api::class);

        return $apiRepo->findAll()[0];
    }

    /**
     * Call the weather API service and return a parsed jSon.
     * Get the history and add unix timestamp for it.
     *
     * @param int $amountDays
     *
     * @return array
     * @throws \Exception
     *
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function callApiHistory(int $amountDays = 30): array
    {
        $resultObject = [];

        $x = $amountDays;

        while($x > 0 )
        {
            $date = mktime(0, 0, 0, date("m"), date("d")-$x,   date("Y"));

            $resultObject[] = $this->callApiForHistory($date);

            $x--;
        }

        return $resultObject;
    }

    /**
     * @param int $time
     *
     * @return array
     *
     * @throws DarkskyException
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    private function callApiForHistory(int $time): array
    {
        $res = [];
        $apiRepo = $this->em->getRepository(Api::class);
        $params = $apiRepo->findAll()[0];
        dd($params);
        $exculdedBlocks = ['hourly', 'currently', 'flags'];
        $client = new GuzzleHttp\Client();
        $basic_url = 'https://api.darksky.net/forecast/44815b156a85b87d4e55264d9b1f176b/50.8465573,4.351697';

        try {

            $responses = $client->send([
                $client->get('/' . urlencode($basic_url) . '/' . $time, $exculdedBlocks),
            ]);

            foreach ($responses as $response)
            {
                $res[] = $response->getBody();
            }

            dd($res->getStatusCode());

//            //TODO adapt this with Guzzle multiple
//            $result = (new Darksky($params->getApiKey()))->timeMachine($params->getCity()->getLatitude(), $params->getCity()->getLongitude(), $time, $exculdedBlocks);
//            $res = json_decode($result, true)['daily']['data'][0];
//
//            if ($res === null) {
//                throw new Exception('No data has been returned from API!');
//            }

        } catch (GuzzleException $e) {
            echo 'The following exceptions were encountered:' . PHP_EOL;
            foreach ($e as $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }
        }

        $result = $this->filterDailyInfo($res);

        return $result;
    }

    /**
     * Filter the data returned by the api so only used data would appear in frontend.
     *
     * @param array $res
     *
     * @return array
     */
    private function filterDailyInfo($res): array
    {
        foreach(self::UNSET_FIELDS as $unsetvalue)
        {
            if(array_key_exists($unsetvalue, $res))
            {
                unset($res[$unsetvalue]);
            }
        }

        return $res;
    }

}