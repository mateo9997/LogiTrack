<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\UserService;

#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(private UserService $userService) {}

    /**
     * Lists all users.
     * Access: ROLE_ADMIN only, or open to ROLE_COORDINATOR if you desire partial visibility.
     */
    #[Route('', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(): JsonResponse
    {
        try {
            return $this->json($this->userService->listUsers());
        } catch (\throwable $exception) {
            return new JsonResponse(['error' => 'unable to fetch list of users', $exception->getMessage()], 500);
        }
    }

    /**
     * Creates a new user with username/password/email/role.
     * Access: ROLE_ADMIN only.
     */
    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            return $this->json($this->userService->createUser($data), 201);
        } catch (\throwable $exception) {
            return new JsonResponse(['error' => 'unable to create user', $exception->getMessage()], 500);
        }
    }

    /**
     * Retrieves a single user by ID.
     * Access: ROLE_ADMIN only (or expand to coordinator if needed).
     */
    #[Route('/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(int $id): JsonResponse
    {
        try{
            return $this->json($this->userService->getUserDetail($id), 201);
        }catch(\throwable $exception){
            return new JsonResponse(['error' => 'user not found', $exception->getMessage()], 404);
        }
    }

    /**
     * Updates a user's info (username, password, role, etc.)
     * Access: ROLE_ADMIN only.
     */
    #[Route('/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            return $this->json($this->userService->updateUser($id, $data));
        }catch(\throwable $exception){
            return new JsonResponse(['error' => 'unable to update user', $exception->getMessage()], 500);
        }

    }

    /**
     * Deletes a user by ID.
     * Access: ROLE_ADMIN only.
     */
    #[Route('/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        try{
            $this->userService->deleteUser($id);
            return new JsonResponse(null, 204);
        }catch(\throwable $exception){
            return new JsonResponse(['error' => 'unable to delete user', $exception->getMessage()], 500);
        }
    }
}
