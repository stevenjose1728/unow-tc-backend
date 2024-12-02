<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequest
{
  private array $errors = [];
  protected array $data = [];

  public function __construct(
    protected ValidatorInterface $validator
  ) {}

  public function getErrors(): array
  {
    return $this->errors;
  }

  public function validate(Request $request): bool
  {
    $this->data = json_decode($request->getContent(), true) ?? [];

    foreach ($this->data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }

    $violations = $this->validator->validate($this);

    $this->errors = [];
    foreach ($violations as $violation) {
      $this->errors[$violation->getPropertyPath()] = $violation->getMessage();
    }

    return empty($this->errors);
  }

  public function getData(): array
  {
    return $this->data;
  }
}
