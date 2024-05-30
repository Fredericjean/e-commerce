<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
        ) {
        }
        
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User;
        $user->setFirstName('Pierre')
            ->setLastName('Bertrand')
            ->setTelephone('0606060606')
            ->setEmail('pierre@example.com')
            ->setPassword(
                $this->hasher->hashPassword($user, 'Test1234')
            ) // Nous devons hasher le password
            ->setRoles(["ROLE_ADMIN"])
            ->setBirthDate(new DateTime('2000/02/02'));

        $manager->persist($user);
        $manager->flush();
    }
}
