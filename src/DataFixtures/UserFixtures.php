<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {

    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('allan@allan.com');
        $user->setRoles(['ROLE_ADMIN']);
        $plainPassword = $user->getEmail();
        $encoded = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($encoded);
        $this->addReference($user->getEmail(), $user);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('max@max.com');
        $user->setRoles(['ROLE_USER']);
        $plainPassword = $user->getEmail();
        $encoded = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($encoded);
        $this->addReference($user->getEmail(), $user);
        $manager->persist($user);

        $manager->flush();
    }
}