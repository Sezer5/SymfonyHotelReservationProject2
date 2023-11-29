<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles',ChoiceType::class,[
                'choices'=>[
                    'ADMIN'=>'ROLE_ADMIN',
                    'USER'=>'ROLE_USER'
                ]
            ])
            ->add('password')
            ->add('name')
            ->add('surname')
            ->add('image')
            ->add('status')
            ->add('created_at')
            ->add('updated_at')
        ;
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray){
                //Transform the array to string
                return count($rolesArray)? $rolesArray[0]: null;
            },
            function ($rolesString){
                //transform the string back to an array
                return[$rolesString];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
