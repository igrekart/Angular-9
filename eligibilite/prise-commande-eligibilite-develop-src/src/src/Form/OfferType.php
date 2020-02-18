<?php

namespace App\Form;

use App\Entity\Offer;
use App\Entity\Option;
use App\Repository\OfferRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('offer', EntityType::class, [
                'class' => Offer::class,
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => false
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => true
            ])
        ;



//        $builder->addEventListener(FormEvents::PRE_SUBMIT, FormEvent $)
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
