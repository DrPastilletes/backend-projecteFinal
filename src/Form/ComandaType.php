<?php

namespace App\Form;

use App\Entity\Comanda;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComandaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('acabat')
            ->add('horaAcabat')
            ->add('preuTotal')
            ->add('comentari')
            ->add('taula')
            ->add('productes')
            ->add('bar')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comanda::class,
        ]);
    }
}
