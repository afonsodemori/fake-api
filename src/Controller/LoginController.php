<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $user = $this->userRepository->findOneBy(['email' => $data->email]);

        if (!isset($data->email) || !isset($data->password)) {
            return $this->json([
                'error' => 'Provide email and password',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->encoder->isPasswordValid($user, $data->password)) {
            return $this->json([
                'error' => 'Bad credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWT::encode(['email' => $user->getEmail()], 'myverysecretkey');

        return $this->json([
            'token' => $token,
        ]);
    }
}
