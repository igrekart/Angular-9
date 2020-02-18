<?php


namespace App\Form;


use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;


class OrderFlow extends FormFlow {

    protected $handleFileUploads = false;

    protected $handleFileUploadsTempDir = '/public/uploads/tmpFlow';


    protected function loadStepsConfig() {
        return [

            [
                'label' => 'customer',
                'form_type' => CustomerType::class,
//                'skip' => true

                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $flow->getFormData()->getStep() > 1;
                }
            ],
            [
                'label' => 'location',
                'form_type' => LocationType::class,
//                'skip' => true
                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $flow->getFormData()->getStep() > 2;
                }

            ],
            [
                'label' => 'eligibilite',
                'form_type' => EligibilityType::class,
//                'skip' => true
                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $flow->getFormData()->getStep() > 3;
                }
            ],
            [
                'label' => 'justification',
                'form_type' => JustificationType::class,
//                'skip' => true
                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $flow->getFormData()->getStep() > 4;
                }
            ],
            [
                'label' => 'offer',
                'form_type' => OfferType::class,
                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $flow->getFormData()->getStep() > 5;
                }
//                'skip' => true
            ],
            [
                'label' => 'payment',
                'form_type' => PaymentType::class
            ]
        ];
    }

}