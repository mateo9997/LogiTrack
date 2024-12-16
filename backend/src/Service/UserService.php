<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $paswordHasher
    ) {}

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUser(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data): User
    {
        $user = new User();
        $user->setUsername($data['username']);
        $hashedPassword = $this->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $role = $this->roleRepository->findOneBy(['name' => $data['role']]);
        $user->setRole($role);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function updateUser(int $id, array $data): ?User
    {
        $user = $this->getUser($id);
        if (!$user) return null;

        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }

        if (isset($data['password'])) {
            $hashedPassword = $this->paswordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (isset($data['role'])) {
            $role = $this->roleRepository->findOneBy(['name' => $data['role']]);
            $user->setRole($role);
        }

        $user->setUpdatedAt(new DateTime());
        $this->entityManager->flush();
        return $user;
    }

    public function deleteUser(int $id): void{
        $user = $this->getUser($id);
        if ($user){
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }
}