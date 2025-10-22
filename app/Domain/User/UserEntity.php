<?php

namespace App\Domain\User;

use App\Models\User as UserModel;
use Carbon\Carbon;

class UserEntity
{
  private int $id;
  private string $name;
  private string $email;
  private string $nip;
  private ?string $sessionToken;
  private ?Carbon $lastLoginAt;
  private ?Carbon $emailVerifiedAt;
  private Carbon $createdAt;
  private Carbon $updatedAt;

  public function __construct(UserModel $user)
  {
    $this->id = $user->id;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->nip = $user->nip;
    $this->sessionToken = $user->session_token;
    $this->lastLoginAt = $user->last_login_at;
    $this->emailVerifiedAt = $user->email_verified_at;
    $this->createdAt = $user->created_at;
    $this->updatedAt = $user->updated_at;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getNip(): string
  {
    return $this->nip;
  }

  public function getSessionToken(): ?string
  {
    return $this->sessionToken;
  }

  public function getLastLoginAt(): ?Carbon
  {
    return $this->lastLoginAt;
  }

  public function getEmailVerifiedAt(): ?Carbon
  {
    return $this->emailVerifiedAt;
  }

  public function getCreatedAt(): Carbon
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): Carbon
  {
    return $this->updatedAt;
  }

  public function setSessionToken(?string $token): void
  {
    $this->sessionToken = $token;
  }

  public function setLastLoginAt(?Carbon $lastLoginAt): void
  {
    $this->lastLoginAt = $lastLoginAt;
  }

  public function hasActiveSession(): bool
  {
    return !is_null($this->sessionToken);
  }

  public function isEmailVerified(): bool
  {
    return !is_null($this->emailVerifiedAt);
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'email' => $this->email,
      'nip' => $this->nip,
      'session_token' => $this->sessionToken,
      'last_login_at' => $this->lastLoginAt,
      'email_verified_at' => $this->emailVerifiedAt,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
    ];
  }
}
