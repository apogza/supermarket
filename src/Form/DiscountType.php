<?php

namespace App\Form;

use App\Entity\Discount;
use App\Entity\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('prodId', EntityType::class, [
            'label' => 'Product: ',
            'class' => Product::class,
            'choice_label' => 'name'
        ])
        ->add('units', NumberType::class, ['label' => 'Units: '])
        ->add('price', NumberType::class, ['label' => 'Price: '])
        ->add('save', SubmitType::class, ['label' => 'Save']);
    }
}
