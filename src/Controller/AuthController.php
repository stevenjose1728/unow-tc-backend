<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;

#[Route('/api')]
class AuthController extends AbstractController
{
  public function __construct(
    private JWTTokenManagerInterface $jwtManager,
    private TokenStorageInterface $tokenStorage
  ) {}

  #[Route('/login', name: 'api_login', methods: ['POST'])]
  public function login(): JsonResponse
  {
    $user = $this->getUser();

    if (!$user instanceof User) {
      return $this->json(['message' => 'No autorizado'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    $token = $this->jwtManager->create($user);

    return $this->json([
      'token' => $token,
      'user' => [
        'id' => $user->getId(),
        'email' => $user->getEmail(),
        'firstName' => $user->getFirstName(),
        'lastName' => $user->getLastName(),
        'position' => $user->getPosition(),
        'birthDate' => $user->getBirthDate()->format('Y-m-d'),
        'roles' => $user->getRoles()
      ]
    ]);
  }
}
