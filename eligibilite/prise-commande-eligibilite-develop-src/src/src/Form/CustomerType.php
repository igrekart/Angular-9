<?php

namespace App\Form;

use App\Entity\Civility;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    private function getConfig(string $placeholder, string $label) {
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
            ->add('civility', EntityType::class, [
                'class' => Civility::class,
                'choice_label' => 'label',
            ] )
            ->add('lastName', TextType::class, $this->getConfig('Nom*', 'Nom'))
            ->add('firstName', TextType::class, $this->getConfig('Prenom*', 'Prenoms'))
            ->add('birth', BirthdayType::class, [
                'widget' => 'single_text',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'label' => "Date de naissance"
            ])
            ->add('birthPlace', TextType::class, $this->getConfig('Lieu de naissance*', 'Lieu de naissance'))
            ->add('nationality', TextType::class, $this->getConfig('Nationalite*', 'Nationalite'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->resolverArray);
    }

}
