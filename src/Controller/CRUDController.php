<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class CRUDController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ObjectRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return $this->json($this->repository->findAll());
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        return $this->json($this->repository->find($id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    abstract public function create(Request $request): JsonResponse;

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    abstract public function update(int $id, Request $request): JsonResponse;

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $user = $this->repository->find($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
