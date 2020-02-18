<?php

namespace App\DataFixtures;

use App\Entity\Civility;
use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();


        $civility = new Civility();
        $civility->setLabel('Mr')
            ->setCode('250');

        $manager->persist($civility);

        $user = new User();

        $hash = $this->encoder->encodePassword($user, 'password');
        $user->setFirstname($faker->firstName)
            ->setLastname($faker->name)
            ->setMail($faker->email)
            ->setPassword($hash)
            ->setPhone($faker->phoneNumber)
            ->setCivility($civility)
            ->setLogin('marc');

        $manager->persist($user);


        $role = new UserRole();
        $role->setCode('111')
            ->setLabel('ADMIN');

        $role->addUser($user);


        $manager->persist($role);






        $manager->flush();
    }
}
