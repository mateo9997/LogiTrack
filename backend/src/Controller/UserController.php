<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\UserService;

class UserController extends AbstractController
{

    public function __construct(private UserService $userService)
    {
    }

    #[Route('/api/users', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return $this->json($users, Response::HTTP_OK);
    }

    #[Route('/api/users', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->createUser($data);
        return $this->json($user, Response::HTTP_CREATED);
    }

    #[Route('/api/users/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(int $id): JsonResponse
    {
        $user = $this->userService->getUser($id);
        return $this->json($user ?? [], $user ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route('api/users/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->getUser($id, $data);
        return $this->json($user ?? [], $user ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/users/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}