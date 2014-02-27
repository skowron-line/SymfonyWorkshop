<?php

namespace GoldenLine\ProductBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/produkt/dodaj');

        $this->assertTrue($crawler->filter('html:contains("Dodaj produkt")')->count() > 0);
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
    }
}
