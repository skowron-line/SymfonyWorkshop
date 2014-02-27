<?php

namespace GoldenLine\ProductBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/produkt/dodaj');

        $this->assertTrue($crawler->filter('html:contains("Dodaj produkt")')->count() > 0);
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());

        $form = $crawler->selectButton('product_save')->form();

        $form['product[name]']  = 'Coca Cola';
        $form['product[price]'] = '1,23';
        $form['product[file]']  = new UploadedFile(
            __DIR__ . '/../Resources/public/image/photo.jpg',
            'photo.jpg',
            'image/jpeg',
            123
        );

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
    }

    /**
     * @test
     */
    public function show()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/produkt/pokaz');

        $this->assertTrue($crawler->filter('html:contains("Produkty")')->count() > 0);
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
    }
}
