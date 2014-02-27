<?php

namespace GoldenLine\ProductBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'product';
    }
}
