<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('admin@gmail.com');
        $admin->setLastname('Ismail');
        $admin->setFirstname('Idb');
        $admin->setAddress('3 Rue Emmanuel Arago');
        $admin->setZipcode('93130');
        $admin->setCity('Noisy-le-sec');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, '123456')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');
        for ($us = 1; $us <= 5; $us++) {
            $user = new Users();
            $user->setEmail($faker->email());
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());
            $user->setAddress($faker->streetAddress());
            $user->setZipcode(str_replace(' ', '', $faker->postcode()));
            $user->setCity($faker->city());
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, '123456')
            );
            
            $manager->persist($user);
        }
        $manager->flush();
    }
}