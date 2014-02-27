<?php

namespace GoldenLine\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GoldenLine\ProductBundle\Model\Product;

class ProductController extends Controller
{
    public function createAction()
    {
        $product = new Product();
        $form    = $this->createFormBuilder($product)
            ->add('name', 'text')
            ->add('price', 'money')
            ->add('photo', 'file')
            ->add('save', 'submit')
            ->getForm();

        return $this->render(
            'ProductBundle:Product:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
