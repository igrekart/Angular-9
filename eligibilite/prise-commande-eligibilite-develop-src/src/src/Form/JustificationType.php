<?php

namespace App\Form;

use App\Entity\Authority;
use App\Entity\Country;
use App\Entity\Identity;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JustificationType extends AbstractType
{
    private function getConfig(string $placeholder, string $label) {
        return [
            'attr' => [
                'placeholder' => $placeholder
            ],
            'label' => $label
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identity', EntityType::class, [
                'class' => Identity::class,
                'choice_label' => 'label',
                'label' => "Piece d'identite"
            ])
            ->add('authority', EntityType::class, [
                'class' => Authority::class,
                'choice_label' => 'label',
                'label' => "Autorite"
            ])
            ->add('identifier', TextType::class, $this->getConfig('Numero de reference', 'Numero de reference'))
            ->add('emission', DateType::class, [
                'widget' => 'single_text',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ]
            ])
            ->add('expiration' , DateType::class, [
                'widget' => 'single_text',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ]
            ])
            ->add('deliveryCountry', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'label',
                'label' => 'Pays'
            ])
            ->add('file', FileType::class, [
                'label' => 'Images',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'multiple' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Uploadez une image valide',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);
    }
}
