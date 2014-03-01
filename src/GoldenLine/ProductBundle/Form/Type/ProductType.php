<?php

namespace GoldenLine\ProductBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use GoldenLine\ProductBundle\Model\Product;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Product $product */
        $product = $options['data'];

        $builder
            ->add('name', 'text')
            ->add(
                'price',
                'money',
                array(
                    'currency' => 'PLN',
                )
            )
            ->add('file', 'file')
            ->add(
                'save',
                'submit',
                array(
                    'label' => $product->isNew()
                            ? 'Zapisz'
                            : 'Zapisz zmiany'
                )
            );
    }

    public function getName()
    {
        return 'product';
    }
}
