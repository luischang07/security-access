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
  private ?Carbon $sessionExpiresAt;
  private ?Carbon $lastLoginAt;
  private ?Carbon $emailVerifiedAt;
  private int $loginAttempts;
  private ?Carbon $loginAttemptsResetAt;
  private ?Carbon $lockedUntil;
  private Carbon $createdAt;
  private Carbon $updatedAt;

  public function __construct(UserModel $user)
  {
    $this->id = $user->id;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->nip = $user->nip;
    $this->sessionToken = $user->session_token;
    $this->sessionExpiresAt = $user->session_expires_at;
    $this->lastLoginAt = $user->last_login_at;
    $this->emailVerifiedAt = $user->email_verified_at;
    $this->loginAttempts = $user->login_attempts ?? 0;
    $this->loginAttemptsResetAt = $user->login_attempts_reset_at;
    $this->lockedUntil = $user->locked_until;
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

  public function getSessionExpiresAt(): ?Carbon
  {
    return $this->sessionExpiresAt;
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

  public function getLoginAttempts(): int
  {
    return $this->loginAttempts;
  }

  public function getLoginAttemptsResetAt(): ?Carbon
  {
    return $this->loginAttemptsResetAt;
  }

  public function getLockedUntil(): ?Carbon
  {
    return $this->lockedUntil;
  }

  public function setSessionToken(?string $token): void
  {
    $this->sessionToken = $token;
  }

  public function setSessionExpiresAt(?Carbon $expiresAt): void
  {
    $this->sessionExpiresAt = $expiresAt;
  }

  public function setLastLoginAt(?Carbon $lastLoginAt): void
  {
    $this->lastLoginAt = $lastLoginAt;
  }

  public function setLoginAttempts(int $attempts): void
  {
    $this->loginAttempts = $attempts;
  }

  public function setLoginAttemptsResetAt(?Carbon $resetAt): void
  {
    $this->loginAttemptsResetAt = $resetAt;
  }

  public function setLockedUntil(?Carbon $lockedUntil): void
  {
    $this->lockedUntil = $lockedUntil;
  }

  public function hasActiveSession(): bool
  {
    return !is_null($this->sessionToken) &&
      !is_null($this->sessionExpiresAt) &&
      $this->sessionExpiresAt->isFuture();
  }

  public function isSessionExpired(): bool
  {
    return !is_null($this->sessionExpiresAt) && $this->sessionExpiresAt->isPast();
  }

  public function isEmailVerified(): bool
  {
    return !is_null($this->emailVerifiedAt);
  }

  public function isLocked(): bool
  {
    return $this->lockedUntil && $this->lockedUntil->isFuture();
  }

  public function shouldResetLoginAttempts(): bool
  {
    return $this->loginAttemptsResetAt && $this->loginAttemptsResetAt->isPast();
  }

  public function canAttemptLogin(): bool
  {
    return !$this->isLocked() && ($this->shouldResetLoginAttempts() || $this->loginAttempts < 4);
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'email' => $this->email,
      'nip' => $this->nip,
      'session_token' => $this->sessionToken,
      'session_expires_at' => $this->sessionExpiresAt,
      'last_login_at' => $this->lastLoginAt,
      'email_verified_at' => $this->emailVerifiedAt,
      'login_attempts' => $this->loginAttempts,
      'login_attempts_reset_at' => $this->loginAttemptsResetAt,
      'locked_until' => $this->lockedUntil,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
    ];
  }
}
