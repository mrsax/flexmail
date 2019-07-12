<?php

namespace App\Tests\Unit\Service;

use App\Service\ApiParameters;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;


/**
 * Class ApiParametersTest
 * @package App\Tests\Unit\Service
 */
class ApiParametersTest extends TestCase
{

    /**
     * Test if the API call is working.
     * @test
     *
     * @covers \App\Service\ApiParameters
     */
    public function callApi(): void
    {
        $client = new Client();

        $response = $client->request('GET', 'https://api.darksky.net/forecast/44815b156a85b87d4e55264d9b1f176b/50.8465573,4.351697');
        $data = json_decode($response->getBody(), true);
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertTrue($response->hasHeader('Date'));
        $this->assertArrayHasKey('offset', $data);
    }
}
