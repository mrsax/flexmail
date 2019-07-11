<?php


namespace App\Service;

use App\Entity\Api;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;


class ApiParameters
{
    private const  API_FORM_INPUT_FIELD_NAME = 'apiName';
    private const  API_FORM_INPUT_METHOD = 'apiMethod';
    private const  API_FORM_INPUT_URL = 'apiUrl';
    private const  API_FORM_INPUT_ENDPOINT = 'apiName';
    private const  API_FORM_INPUT_KEY = 'endpoint';
    private const  API_FORM_INPUT_CITY = 'city';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setApiParameters(array $prams): Api
    {
        $apiRepo = $this->em->getRepository(Api::class);

        $res = $apiRepo->findOneBy(['name' => $prams[self::API_FORM_INPUT_FIELD_NAME]]);

        //update or create api settings input
        if ($res !== null)
        {
            $res->setMethod($prams[self::API_FORM_INPUT_METHOD])
                ->setUrl($prams[self::API_FORM_INPUT_URL])
                ->setEndpoint($prams[self::API_FORM_INPUT_ENDPOINT])
                ->setApiKey($prams[self::API_FORM_INPUT_KEY]);
            $api = $res;
        }
        else
        {
            $api = new Api();

            $api->setName($prams[self::API_FORM_INPUT_FIELD_NAME])
                ->setMethod($prams[self::API_FORM_INPUT_METHOD])
                ->setUrl($prams[self::API_FORM_INPUT_URL])
                ->setEndpoint($prams[self::API_FORM_INPUT_ENDPOINT])
                ->setApiKey($prams[self::API_FORM_INPUT_KEY]);

            $this->em->persist($api);
            $this->em->flush($api);
        }

        return $api;
    }
}