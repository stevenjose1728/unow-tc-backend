<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\User\CreateUserRequest;
use App\Request\User\UpdateUserRequest;
use App\Request\User\UpdateProfileRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;
use Symfony\Component\HttpClient\HttpClient;

#[Route('/users', name: 'api_users_')]
class UserController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private UserPasswordHasherInterface $passwordHasher,
    private UserRepository $userRepository
  ) {}

  #[Route('', name: 'index', methods: ['GET'])]
  public function index(Request $request): JsonResponse
  {
    $page = $request->query->getInt('page', 1);
    $limit = $request->query->getInt('limit', 10);
    $search = $request->query->get('search', '');

    $qb = $this->userRepository->createQueryBuilder('u')
      ->orderBy('u.createdAt', 'DESC');

    if ($search) {
      $qb->where('u.email LIKE :search OR u.firstName LIKE :search OR u.lastName LIKE :search')
        ->setParameter('search', "%$search%");
    }

    $total = (clone $qb)->select('COUNT(u.id)')->getQuery()->getSingleScalarResult();
    $users = $qb->setFirstResult(($page - 1) * $limit)
      ->setMaxResults($limit)
      ->getQuery()
      ->getResult();

    return $this->json([
      'data' => array_map([$this, 'serializeUser'], $users),
      'total' => $total,
      'page' => $page,
      'totalPages' => ceil($total / $limit)
    ]);
  }

  #[Route('', name: 'create', methods: ['POST'])]
  public function create(Request $request, CreateUserRequest $createRequest): JsonResponse
  {
    if (!$createRequest->validate($request)) {
      return $this->json(['errors' => $createRequest->getErrors()], Response::HTTP_BAD_REQUEST);
    }

    $data = $createRequest->getData();

    if ($this->userRepository->findOneBy(['email' => $data['email']])) {
      return $this->json(['message' => 'El email ya está registrado'], Response::HTTP_CONFLICT);
    }

    $user = (new User())
      ->setEmail($data['email'])
      ->setFirstName($data['firstName'])
      ->setLastName($data['lastName'])
      ->setPosition($data['position'])
      ->setBirthDate(new \DateTime($data['birthDate']))
      ->setPassword($this->passwordHasher->hashPassword(new User(), $data['password']))
      ->setCreatedAt(new DateTime())
      ->setRoles(['APPLICANT']);

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    $flaskUrl = $_ENV['EMAIL_SERVICE_URL'] . '/send-email';
    $httpClient = HttpClient::create();
    $response = $httpClient->request('POST', $flaskUrl, [
      'json' => [
        'email' => $data['email'],
        'firstName' => $data['firstName'],
        'lastName' => $data['lastName'],
        'position' => $data['position'],
      ],
    ]);

    if ($response->getStatusCode() !== 200) {
      return $this->json(['message' => 'Usuario creado, pero ocurrió un error al enviar el correo'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    return $this->json($this->serializeUser($user), Response::HTTP_CREATED);
  }

  #[Route('/{id}', name: 'show', methods: ['GET'])]
  public function show(int $id): JsonResponse
  {
    $user = $this->userRepository->find($id);

    return $user
      ? $this->json($this->serializeUser($user))
      : $this->json(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
  }

  #[Route('/{id}', name: 'update', methods: ['PUT'])]
  public function update(int $id, Request $request, UpdateUserRequest $updateRequest): JsonResponse
  {
    if (!$updateRequest->validate($request)) {
      return $this->json(['errors' => $updateRequest->getErrors()], Response::HTTP_BAD_REQUEST);
    }

    $user = $this->userRepository->find($id);
    if (!$user) {
      return $this->json(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
    }

    $data = $updateRequest->getData();

    if (isset($data['email']) && $this->userRepository->findOneBy(['email' => $data['email']])?->getId() !== $id) {
      return $this->json(['message' => 'El email ya está registrado'], Response::HTTP_CONFLICT);
    }

    $user->setEmail($data['email'] ?? $user->getEmail())
      ->setFirstName($data['firstName'] ?? $user->getFirstName())
      ->setLastName($data['lastName'] ?? $user->getLastName())
      ->setPosition($data['position'] ?? $user->getPosition())
      ->setBirthDate(isset($data['birthDate']) ? new \DateTime($data['birthDate']) : $user->getBirthDate());

    $this->entityManager->flush();

    return $this->json($this->serializeUser($user));
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(int $id): JsonResponse
  {
    $user = $this->userRepository->find($id);

    if (!$user) {
      return $this->json(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
    }

    $this->entityManager->remove($user);
    $this->entityManager->flush();

    return $this->json(null, Response::HTTP_NO_CONTENT);
  }

  #[Route('/me', name: 'profile', methods: ['GET'])]
  public function profile(): JsonResponse
  {
    return $this->json($this->serializeUser($this->getUser()));
  }

  private function serializeUser(User $user): array
  {
    return [
      'id' => $user->getId(),
      'email' => $user->getEmail(),
      'firstName' => $user->getFirstName(),
      'lastName' => $user->getLastName(),
      'position' => $user->getPosition(),
      'birthDate' => $user->getBirthDate()?->format('Y-m-d'),
      'roles' => $user->getRoles(),
      'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s')
    ];
  }
}
