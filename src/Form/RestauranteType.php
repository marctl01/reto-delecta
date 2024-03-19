<?php

namespace App\Form;

use App\Entity\Restaurante;
use App\Entity\Segmento;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestauranteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('street_address')
            ->add('latitude')
            ->add('longitude')
            ->add('city_name')
            ->add('popularity_rate')
            ->add('satisfaction_rate')
            ->add('last_avg_price')
            ->add('total_reviews')
            ->add('uidentifier')
            ->add('segmentos', EntityType::class, [
                'class' => Segmento::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurante::class,
        ]);
    }
}
