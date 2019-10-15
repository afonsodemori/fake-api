<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/users", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent());
        $user = (new User())
            ->setEmail($data->email)
            ->setName($data->name)
            ->setSurname($data->surname)
            ->setPassword(password_hash($data->password, PASSWORD_ARGON2I))
            ->setBirthdate(new \DateTime($data->birthdate));

        $em->persist($user);
        $em->flush();

        return $this->json([], 204);
    }
}
