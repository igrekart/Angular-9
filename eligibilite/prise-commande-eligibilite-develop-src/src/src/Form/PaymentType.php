<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\PaymentChoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentChoice', EntityType::class, [
                'choice_label' => 'label',
                'class' => PaymentChoice::class
            ])
            ->add('amount', MoneyType::class, [
                'attr' => [
                    'placeholder' => 'Montant'
                ],
                'label' => 'Montant paye'
            ])
            ->add('check', CollectionType::class, [
                'entry_type' => BankCheckType::class,
                'allow_add' => true
            ])
            ->add('mobileMoney', CollectionType::class, [
                'entry_type' => MobileMoneyType::class,
                'allow_add' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
