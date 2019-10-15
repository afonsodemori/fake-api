<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends CRUDController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserController constructor.
     * @param UserRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager)
    {
        parent::__construct($repository, $entityManager);
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $user = (new User())
            ->setEmail($data->email)
            ->setName($data->name)
            ->setSurname($data->surname)
            ->setPassword(password_hash($data->password, PASSWORD_ARGON2I))
            ->setBirthdate(new \DateTime($data->birthdate));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $user = $this->repository->find($id);
        $user
            ->setEmail($data->email)
            ->setName($data->name)
            ->setSurname($data->surname)
            ->setBirthdate(new \DateTime($data->birthdate));

        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
