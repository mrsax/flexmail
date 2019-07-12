<?php


namespace App\Tests\Functional\General;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class GeneralTest
 * @package App\Tests\Functional\General
 */
class GeneralTest extends WebTestCase
{
    /**
     * Test the url's.
     * @test
     * @param $url
     * @dataProvider provideUrls
     *
     */
    public function pages(string $url): void
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Check if the view is properly created.
     * @test
     * @dataProvider providerContent
     * @param string $contentText
     */
    public function forecastView(string $contentText): void
    {
        $client = static::createClient();

        $client->request('GET', '/weather/forecast');
        $content = $client->getResponse()->getContent();
        $this->assertContains($contentText, $content);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    //################################################ Data Providers ####################################

    /**
     * @for 'pages'
     *
     * @return array
     */
    public function provideUrls(): array
    {
        return [
            ['/'],
            ['/weather/forecast'],
            ['/weather/history'],
            ['/api/doc.json'],
        ];
    }


    /**
     * @for 'views'
     * @return array
     */
    public function providerContent(): array
    {
        return[
            ['Forecast for'],
            ['day-container'],
            ['app.js'],
            ['Kmh'],
            ['ozone'],
            ['°C'],
            ['windicon'],
            ['general-info'],
        ];
    }

}