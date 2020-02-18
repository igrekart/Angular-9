<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    private function getConfig(string $placeholder, string  $label) {
        return [
            'attr' => [
                'placeholder' => $placeholder
            ],
            'label' => $label
        ];
    }

    private $resolverArray = ["allow_extra_fields" => true];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'label',
                'label' => 'Pays'
            ])
            ->add('city', TextType::class, $this->getConfig('Ville*', 'Ville'))
            ->add('town', TextType::class, $this->getConfig('Commune*', 'Commune'))
            ->add('district', TextType::class, $this->getConfig('Quartier*', 'Quartier'))
            ->add('addition', TextareaType::class,  array_merge($this->getConfig("Complement d'adresse", 'Complement d\'adresse'), ['required' => false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->resolverArray);
    }
}
