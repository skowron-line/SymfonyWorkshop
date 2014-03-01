<?php

namespace GoldenLine\ProductBundle\Controller;

use GoldenLine\ProductBundle\Form\Type\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GoldenLine\ProductBundle\Model\Product;
use Symfony\Component\HttpFoundation\Request;

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
}
