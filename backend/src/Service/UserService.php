<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUser(int $id): object
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data): User
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $role = $this->roleRepository->findOneBy(['name' => $data['role']]);
        $user->setRole($role);

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser(int $id, array $data): ?object
    {
        $user = $this->getUser($id);
        if (!$user) return null;

        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }

        if(isset($data['email'])){
            $user->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }
        if (isset($data['role'])) {
            $role = $this->roleRepository->findOneBy(['name' => $data['role']]);
            $user->setRole($role);
        }

        $user->setUpdatedAt(new DateTime());
        $this->em->flush();
        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->getUser($id);
        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }
    }
}
