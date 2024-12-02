<?php

namespace App\Request\User;

use Symfony\Component\Validator\Constraints as Assert;
use App\Request\BaseRequest;
use App\Request\AbstractRequest;

class UpdateProfileRequest extends AbstractRequest
{
  #[Assert\Length(min: 2, minMessage: 'El nombre debe tener al menos {{ limit }} caracteres')]
  protected ?string $firstName = null;

  #[Assert\Length(min: 2, minMessage: 'El apellido debe tener al menos {{ limit }} caracteres')]
  protected ?string $lastName = null;

  protected ?string $position = null;

  #[Assert\Date(message: 'La fecha de nacimiento no es válida')]
  protected ?string $birthDate = null;

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function getPosition(): ?string
  {
    return $this->position;
  }

  public function getBirthDate(): ?string
  {
    return $this->birthDate;
  }
}
