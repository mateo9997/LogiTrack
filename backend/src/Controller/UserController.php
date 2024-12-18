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

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        try {
            return $this->json($this->userService->getAllUsers());
        } catch (\throwable $exception) {
            return new JsonResponse(['error' => 'unable to fetch list of users', $exception->getMessage()], 500);
        }
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            return $this->json($this->userService->createUser($data), 201);
        } catch (\throwable $exception) {
            return new JsonResponse(['error' => 'unable to create user', $exception->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods: ['GET'])]
    public function detail(int $id): JsonResponse
    {
        try{
            return $this->json($this->userService->getUserDetail($id), 201);
        }catch(\throwable $exception){
            return new JsonResponse(['error' => 'user not found', $exception->getMessage()], 404);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            return $this->json($this->userService->updateUser($id, $data));
        }catch(\throwable $exception){
            return new JsonResponse(['error' => 'unable to update user', $exception->getMessage()], 500);
        }

    }

    #[Route('/{id}', methods: ['DELETE'])]
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
