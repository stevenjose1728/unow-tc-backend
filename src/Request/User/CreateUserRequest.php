<?php

namespace App\Request\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use App\Request\AbstractRequest;

class CreateUserRequest extends AbstractRequest
{
    #[Assert\NotBlank(message: 'El email es requerido')]
    #[Assert\Email(message: 'El email no es válido')]
    protected ?string $email = null;

    #[Assert\NotBlank(message: 'La contraseña es requerida')]
    #[Assert\Length(min: 6, minMessage: 'La contraseña debe tener al menos {{ limit }} caracteres')]
    protected ?string $password = null;

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
