<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Role;
use DateTime;

class UserServiceTest extends TestCase
{
    public function testCreateUser()
    {
        $userRepo = $this->createMock(UserRepository::class);
        $roleRepo = $this->createMock(RoleRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $role = new Role();
        $role->setName('ROLE_COORDINATOR');

        $roleRepo->method('findOneBy')->willReturn($role);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');
        $hasher->method('hashPassword')->willReturn('hashed_password');

        $service = new UserService($userRepo, $roleRepo, $em, $hasher);
        $userData = [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'secret',
            'role' => 'ROLE_COORDINATOR'
        ];
        $user = $service->createUser($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('johndoe', $user->getUsername());
        $this->assertEquals('john@example.com', $user->getEmail());
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertEquals('ROLE_COORDINATOR', $user->getRole()->getName());
    }

    public function testUpdateUser()
    {
        $userRepo = $this->createMock(UserRepository::class);
        $roleRepo = $this->createMock(RoleRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $user = new User();
        $user->setUsername('olduser');
        $user->setEmail('old@example.com');
        $user->setPassword('oldpass');

        $newRole = new Role();
        $newRole->setName('ROLE_ADMIN');

        // Mock repository calls
        $userRepo->method('find')->with(123)->willReturn($user);
        $roleRepo->method('findOneBy')->with(['name' => 'ROLE_ADMIN'])->willReturn($newRole);
        $hasher->method('hashPassword')->willReturn('new_hashed_password');
        $em->expects($this->once())->method('flush');

        $service = new UserService($userRepo, $roleRepo, $em, $hasher);
        $updatedData = [
            'username' => 'newuser',
            'email' => 'new@example.com',
            'password' => 'newpass',
            'role' => 'ROLE_ADMIN'
        ];
        $updatedUser = $service->updateUser(123, $updatedData);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals('newuser', $updatedUser->getUsername());
        $this->assertEquals('new@example.com', $updatedUser->getEmail());
        $this->assertEquals('new_hashed_password', $updatedUser->getPassword());
        $this->assertEquals('ROLE_ADMIN', $updatedUser->getRole()->getName());
    }

    public function testUpdateUserNotFound()
    {
        $userRepo = $this->createMock(UserRepository::class);
        $roleRepo = $this->createMock(RoleRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        // User not found scenario
        $userRepo->method('find')->with(999)->willReturn(null);

        $em->expects($this->never())->method('flush');

        $service = new UserService($userRepo, $roleRepo, $em, $hasher);
        $updatedUser = $service->updateUser(999, ['username' => 'doesntmatter']);
        $this->assertNull($updatedUser);
    }

    public function testDeleteUser()
    {
        $userRepo = $this->createMock(UserRepository::class);
        $roleRepo = $this->createMock(RoleRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $user = new User();
        $userRepo->method('find')->with(456)->willReturn($user);

        // Expect remove and flush
        $em->expects($this->once())->method('remove')->with($user);
        $em->expects($this->once())->method('flush');

        $service = new UserService($userRepo, $roleRepo, $em, $hasher);
        $service->deleteUser(456);
        $this->assertTrue(true); // If no exceptions thrown, test passes
    }

    public function testDeleteUserNotFound()
    {
        $userRepo = $this->createMock(UserRepository::class);
        $roleRepo = $this->createMock(RoleRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher = $this->createMock(UserPasswordHasherInterface::class);

        $userRepo->method('find')->with(777)->willReturn(null);

        $em->expects($this->never())->method('remove');
        $em->expects($this->never())->method('flush');

        $service = new UserService($userRepo, $roleRepo, $em, $hasher);
        $service->deleteUser(777);
        $this->assertTrue(true);
    }
}
