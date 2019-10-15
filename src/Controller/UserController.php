<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function findAll(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll());
    }

    /**
     * @Route("/users/{id}", methods={"GET"})
     * @param int $id
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function findOne(int $id, UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->find($id));
    }
}
