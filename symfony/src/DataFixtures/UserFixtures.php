<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $usersData = [
            0 => [
                'email' => '1111@mail.ru',
                'role' => ['ROLE_USER'],
                'password' => 2222,
                'confirmationCode' => '',
                'enabled' => '1'
            ],
            1 => [
                'email' => '2222@mail.ru',
                'role' => ['ROLE_ADMIN'],
                'password' => 2222,
                'confirmationCode' => '',
                'enabled' => '1'
            ]
        ];

        foreach ($usersData as $user) {
            $newUser = new User();
            $newUser->setEmail($user['email']);
            $newUser->setPassword($this->hasher->hashPassword($newUser, $user['password']));
            $newUser->setRoles($user['role']);
            $newUser->setEnable($user['enabled']);
            $newUser->setConfirmationCode($user['confirmationCode']);
            $manager->persist($newUser);
        }
        $manager->flush();
    }
}