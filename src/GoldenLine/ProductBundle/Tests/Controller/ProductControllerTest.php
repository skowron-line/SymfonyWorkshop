<?php

namespace GoldenLine\ProductBundle\Tests\Controller;

use GoldenLine\ProductBundle\Model\ProductQuery;
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
        $form['product[amount]'] = '5';
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
     * @depends testCreate
     */
    public function show()
    {
        $client  = static::createClient();
        $product = ProductQuery::create()->findOne();
        $crawler = $client->request('GET', '/produkt/pokaz/' . $product->getId());

        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
    }

    /**
     * @test
     * @depends testCreate
     */
    public function listProducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/produkty/lista');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertGreaterThan(0, $crawler->filter('h1')->count());

        $this->assertEquals('Lista produktów' ,$crawler->filter('h1')->text());
    }

    /**
     * @test
     * @depends testCreate
     */
    public function buyProduct()
    {
        $product   = ProductQuery::create()->findOne();
        $oldAmount = $product->getAmount();

        $client = static::createClient();
        $uri    = sprintf('/produkt/%s/kup', $product->getId());
        $client->request('GET', $uri);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $productAfterRequest = ProductQuery::create()->findOneById($product->getId());

        $this->assertEquals($oldAmount - 1, $productAfterRequest->getAmount());
    }

    /**
     * @depends testCreate
     */
    public function testEdit()
    {
        $client  = static::createClient();
        $product = ProductQuery::create()->findOne();
        $crawler = $client->request('GET', '/produkt/edytuj/' . $product->getId());

        $this->assertTrue($crawler->filter('html:contains("Edytuj produkt")')->count() > 0);
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());

        $form = $crawler->selectButton('product_save')->form();

        $form['product[name]']  = 'Coca Cola Light';
        $form['product[price]'] = '12,34';
        $form['product[amount]'] = '8';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/produkt/pokaz/' . $product->getId()));

        $product = ProductQuery::create()->findOne();

        $this->assertEquals('Coca Cola Light', $product->getName());
        $this->assertEquals(12.34, $product->getPrice());
        $this->assertEquals(8, $product->getAmount());
    }
}
