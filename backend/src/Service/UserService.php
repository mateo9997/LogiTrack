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

    public function listUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUserDetail(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data): User
    {
        $user = new User();
        $user->setUsername($data['username']);

        // Optional email
        if (!empty($data['email'])) {
            $user->setEmail($data['email']);
        }

        // Hashed password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Set role
        $role = $this->roleRepository->findOneBy(['name' => $data['role']]);
        $user->setRole($role);

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser(int $id, array $data): ?User
    {
        $user = $this->getUserDetail($id);
        if (!$user) {
            return null;
        }

        if (!empty($data['username'])) {
            $user->setUsername($data['username']);
        }

        if (!empty($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (!empty($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (!empty($data['role'])) {
            $role = $this->roleRepository->findOneBy(['name' => $data['role']]);
            $user->setRole($role);
        }

        $user->setUpdatedAt(new DateTime());
        $this->em->flush();
        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->getUserDetail($id);
        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }
    }
}
