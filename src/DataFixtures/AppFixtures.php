<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Gender;
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
            ->setEmail('admin@test.com')
            ->setPassword(
                $this->hasher->hashPassword($user, 'Test1234')
            ) // Nous devons hasher le password
            ->setRoles(["ROLE_ADMIN"])
            ->setBirthDate(new DateTime('2000/02/02'));

        $manager->persist($user);
        

        $genders = ['Homme', 'Femme', 'Enfant', 'Adolescent'];
        foreach ($genders as $genderName){
            $gender = new Gender;
            $gender->setName($genderName);
            $gender->setEnable(true);
            $manager->persist($gender);
        }
        $manager->flush();
    }
}
