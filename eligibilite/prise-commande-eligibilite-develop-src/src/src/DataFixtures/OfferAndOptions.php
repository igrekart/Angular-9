<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class OfferAndOptions extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();




        $options = [
            'Fiberbox' => 0,
            'Decodeur TV' => 15000,
            'Pack multimedia (Fiberbox & Décodeur TV)' => 35000,
            'Pack multimedia' => 0,

        ];

        $offers = [
            'Zen Mini' => 19000,
            'Zen Plus' => 20000,
            'Zen Max' => 29000,
            'Play Mini' => 35000,
            'Play' => 55000,
            'Jet' => 75000,
            'Super Jet' => 85000
        ];

        $savedOptions = [];


        foreach ($options as $option => $price) {
            $toSave = new Option();
            $toSave->setLabel($option)
                ->setPrice($price)
                ->setCode(250);

            $manager->persist($toSave);

            $savedOptions[]= $toSave;
        }


        foreach ($offers as $offer => $price) {
            $toSave = new Offer();
            $toSave
                ->setLabel($offer)
                ->setAmount($price)
                ->setDescription(join($faker->sentences(3)))
            ;

            switch ($offer) {
                case 'Zen Plus':
                case 'Zen Mini':
                    $toSave->addOption($savedOptions[0]);
                    break;
                case 'Zen Max':
                    $toSave->addOption($savedOptions[0])
                        ->addOption($savedOptions[1]);
                    break;
                case 'Play Mini':
                case 'Play':
                    $toSave->addOption($savedOptions[2]);
                    break;
                case 'Jet':
                case 'Super Jet':
                    $toSave->addOption($savedOptions[3]);
                    break;
            }

            $manager->persist($toSave);
        }

//        for ($i = 0; $i <= count($offers); $i++) {
//            $offer = new Offer();
//            $offer
//                ->setLabel($faker->sentence)
//                ->setAmount($faker->randomNumber())
//                ->setDescription(join($faker->sentences(3)))
//                ;
//
//            $manager->persist($offer);
//
//            for ($j = 0; $j <3 ; $j++) {
//                $option = new Option();
//                $option
//                    ->setLabel($faker->sentence)
//                    ->setCode($faker->randomNumber(3))
//                    ->setPrice($faker->randomFloat())
//                    ->addOffer($offer);
//                $manager->persist($option);
//            }
//        }

        $manager->flush();
    }
}
