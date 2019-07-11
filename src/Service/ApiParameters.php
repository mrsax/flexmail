<?php


namespace App\Service;

use App\Entity\Api;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Service that provides everything about the Api parameters.
 * Class ApiParameters
 * @package App\Service
 */
class ApiParameters
{
    private const  API_FORM_INPUT_METHOD = 'apiMethod';
    private const  API_FORM_INPUT_URL = 'apiUrl';
    private const  API_FORM_INPUT_ENDPOINT = 'apiName';
    private const  API_FORM_INPUT_KEY = 'endpoint';
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
}