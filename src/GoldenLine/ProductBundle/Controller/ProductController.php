<?php

namespace GoldenLine\ProductBundle\Controller;

use GoldenLine\ProductBundle\Form\Type\ProductType;
use GoldenLine\ProductBundle\Model\ProductQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GoldenLine\ProductBundle\Model\Product;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends Controller
{
    public function createAction(Request $request)
    {
        $product = new Product();
        $form    = $this->createForm(new ProductType(), $product);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $product->save();

            return $this->redirect($this->generateUrl('product_show', array('id' => $product->getId())));
        }

        return $this->render(
            'ProductBundle:Product:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function showAction(Product $product)
    {
        return $this->render('ProductBundle:Product:show.html.twig', array('product' => $product));
    }

    public function deleteAction(Product $product)
    {
        $product->delete();

        return $this->redirect($this->generateUrl('product_create'));
    }

    public function editAction(Request $request, Product $product)
    {
        $form = $this->createForm(new ProductType(), $product);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $product->save();

            return $this->redirect($this->generateUrl('product_show', array('id' => $product->getId())));
        }

        return $this->render(
            'ProductBundle:Product:edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function listAction()
    {
        $products = ProductQuery::create()->find();

        return $this->render(
            'ProductBundle:Product:list.html.twig',
            array(
                'products' => $products,
            )
        );
    }

    public function buyAction(Product $product)
    {
        $params = array(
            'id'           => $this->container->getParameter('transferuj_id'),
            'kwota'        => $product->getPrice(),
            'opis'         => $product->getName(),
            'crc'          => $product->getId(),
            'pow_url'      => $this->generateUrl(
                    'product_success',
                    array('id' => $product->getId()),
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            'pow_url_blad' => $this->generateUrl(
                    'product_failure',
                    array(),
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
        );

        /** @var Router $router */
        $router = $this->get('router');
        $router
            ->getContext()
            ->setBaseUrl('');

        return $this->redirect($router->generate('transferuj_gateway', $params));
    }

    public function successAction(Product $product)
    {
        $product
            ->setAmount($product->getAmount() - 1)
            ->save();

        $this->get('session')->getFlashBag()->add(
            'success',
            'Dziękujemy za zakup!'
        );

        return $this->redirect($this->generateUrl('products_list'));
    }

    public function failureAction()
    {
        $this->get('session')->getFlashBag()->add(
            'error',
            'Zakup nie powiódł się!'
        );

        return $this->redirect($this->generateUrl('products_list'));
    }
}
