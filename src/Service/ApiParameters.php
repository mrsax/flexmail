<?php


namespace App\Service;

use App\Entity\Api;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Darksky\Darksky;
use Darksky\DarkskyException;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


/**
 * Service that provides everything about the Api parameters.
 * Class ApiParameters
 * @package App\Service
 */
final class ApiParameters
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
     * @return array
     * @throws DarkskyException
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
     *
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function callApiHistory(int $amountDays = 30): array
    {
        $apiRepo = $this->em->getRepository(Api::class);
        $params = $apiRepo->findAll()[0];

        $resultObject[] = $this->callApiForHistory($params, $amountDays);

        return $resultObject;
    }

    /**
     * Returns an array of responses.
     *
     * @param Api $params
     * @param string $amountofCalls
     *
     * @return array
     *
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    private function callApiForHistory(Api $params, string $amountofCalls): array
    {
        $res = [];
        $apiRes = [];
        $exculdedBlocks = ['hourly', 'currently', 'flags'];
        $exclude = implode(',', $exculdedBlocks);

        $host = $params->getUrl();
        $path = $params->getEndpoint() . '/' . $params->getApiKey();
        $path .= '/' . $params->getCity()->getLatitude() . ',' . $params->getCity()->getLongitude();

        $client = new CurlHttpClient();
        $responses = [];

        $x = $amountofCalls;

        while($x > 0 )
        {
            $date = mktime(0, 0, 0, date("m"), date("d")-$x,   date("Y"));
            $responses[] = $client->request(strtoupper($params->getMethod()), $host . $path . ',' . $date . '?exclude=' . $exclude);
            $x--;
        }

        foreach ($client->stream($responses, 1.5) as $response => $chunk)
        {
            if ($chunk->isTimeout())
            {
                new Exception('The API is not accessible! Check later');
            }
            try {
                if ($chunk->isFirst())
                {
                    $apiRes[] = $response->getContent();
                }
                elseif ($chunk->isLast())
                {
                    $apiRes[] = $response->getContent();
                }
                else
                {
                    $apiRes[] = $response->getContent();
                }
            } catch (TransportExceptionInterface $e)
            {
                echo $e->getMessage();
            }
        }

        foreach($apiRes as &$response)
        {
            $res[] = $this->filterDailyInfo(json_decode($response, true)['daily']['data'][0]);
        }

        return $res;
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