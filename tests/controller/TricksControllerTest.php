<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    public function testTrickView()
    {
        $client = static::createClient();
        $client->request('GET', '/trickslist/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    /**
     * @dataProvider provideUrls
     * @param $url
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return [
            ['/'],
            ['/register'],
            ['/login'],
        ];
    }
}