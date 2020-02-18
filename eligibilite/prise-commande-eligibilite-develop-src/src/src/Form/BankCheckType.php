<?php

namespace App\Form;

use App\Entity\Bank;
use App\Entity\BankCheck;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankCheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('beneficiary', TextType::class, [
                'label' => 'Beneficiaire'
            ])
            ->add('bank', EntityType::class, [
                'class' => Bank::class,
                'choice_label' => 'label',
                'label' => 'Banque'
            ])
            ->add('numero', TextType::class, [
                'label' => 'Numero de cheque'
            ])
            ->add('amount', MoneyType::class,[
                'label' => 'montant du cheque'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankCheck::class,
        ]);
    }
}
