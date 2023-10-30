<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('image')
            ->add('star')
            ->add('address')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('city')
            ->add('country')
            ->add('location')
            ->add('status')
            ->add('created_at')
            ->add('updated_at')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
