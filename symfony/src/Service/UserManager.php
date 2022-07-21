<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface      $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function create($email, $password): User
    {
        $user = new User();
        $user
            ->setEmail($email)
            ->setPassword($this->hasher->hashPassword($user, $password))
            ->setRoles((array)'ROLE_ADMIN')
            ->setEnable(1)
            ->setConfirmationCode('');

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

}