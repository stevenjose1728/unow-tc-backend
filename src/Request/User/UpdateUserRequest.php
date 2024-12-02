<?php

namespace App\Request\User;

use App\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest extends AbstractRequest
{
  #[Assert\NotBlank(message: 'El email es requerido')]
  #[Assert\Email(message: 'El email no es válido')]
  protected ?string $email = null;

  #[Assert\NotBlank(message: 'El nombre es requerido')]
  #[Assert\Length(min: 2, minMessage: 'El nombre debe tener al menos {{ limit }} caracteres')]
  protected ?string $firstName = null;

  #[Assert\NotBlank(message: 'El apellido es requerido')]
  #[Assert\Length(min: 2, minMessage: 'El apellido debe tener al menos {{ limit }} caracteres')]
  protected ?string $lastName = null;

  #[Assert\NotBlank(message: 'La posición es requerida')]
  protected ?string $position = null;

  #[Assert\NotBlank(message: 'La fecha de nacimiento es requerida')]
  #[Assert\Date(message: 'La fecha de nacimiento no es válida')]
  protected ?string $birthDate = null;

  public function getEmail(): ?string
  {
    return $this->data['email'] ?? null;
  }

  public function getFirstName(): ?string
  {
    return $this->data['firstName'] ?? null;
  }

  public function getLastName(): ?string
  {
    return $this->data['lastName'] ?? null;
  }

  public function getPosition(): ?string
  {
    return $this->data['position'] ?? null;
  }

  public function getBirthDate(): ?string
  {
    return $this->data['birthDate'] ?? null;
  }
}
