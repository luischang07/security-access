<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SingleSessionManager;
use App\Repositories\UserRepository;
use App\Domain\User\UserEntity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class SingleSessionManagerTest extends TestCase
{
  use RefreshDatabase;

  private SingleSessionManager $sessionManager;
  private $userRepository;

  protected function setUp(): void
  {
    parent::setUp();

    $this->userRepository = Mockery::mock(UserRepository::class);
    $this->sessionManager = new SingleSessionManager($this->userRepository);
    $this->artisan('migrate');
  }

  protected function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }

  /** @test */
  public function puede_registrar_sesion_con_duracion_default()
  {
    // Arrange
    $userId = 1;
    $token = 'test-session-token';
    $remember = false;

    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('getId')->andReturn($userId);

    $this->userRepository->shouldReceive('updateSessionDataWithLock')
      ->once()
      ->with(
        $userId,
        $token,
        Mockery::type(\Carbon\Carbon::class),
        Mockery::type(\Carbon\Carbon::class)
      );

    // Act
    $this->sessionManager->registerSession($user, $token, $remember);

    // Assert
    // La verificación se hace a través de los mocks
    $this->assertTrue(true);
  }

  /** @test */
  public function puede_registrar_sesion_con_remember_me()
  {
    // Arrange
    $userId = 1;
    $token = 'test-session-token';
    $remember = true;

    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('getId')->andReturn($userId);

    $this->userRepository->shouldReceive('updateSessionDataWithLock')
      ->once()
      ->with(
        $userId,
        $token,
        Mockery::type(\Carbon\Carbon::class),
        Mockery::type(\Carbon\Carbon::class)
      );

    // Act
    $this->sessionManager->registerSession($user, $token, $remember);

    // Assert
    $this->assertTrue(true);
  }

  /** @test */
  public function puede_verificar_sesion_activa()
  {
    // Arrange
    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('isSessionExpired')->andReturn(false);
    $user->shouldReceive('hasActiveSession')->andReturn(true);

    // Act
    $hasActiveSession = $this->sessionManager->hasActiveSession($user);

    // Assert
    $this->assertTrue($hasActiveSession);
  }

  /** @test */
  public function limpia_sesion_si_esta_expirada()
  {
    // Arrange
    $userId = 1;
    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('isSessionExpired')->andReturn(true);
    $user->shouldReceive('getId')->andReturn($userId);

    $this->userRepository->shouldReceive('clearSession')
      ->once()
      ->with($userId);

    // Act
    $hasActiveSession = $this->sessionManager->hasActiveSession($user);

    // Assert
    $this->assertFalse($hasActiveSession);
  }

  /** @test */
  public function puede_limpiar_sesion()
  {
    // Arrange
    $userId = 1;
    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('getId')->andReturn($userId);

    $this->userRepository->shouldReceive('clearSession')
      ->once()
      ->with($userId);

    // Act
    $this->sessionManager->clearSession($user);

    // Assert
    $this->assertTrue(true);
  }

  /** @test */
  public function valida_token_de_sesion_correctamente()
  {
    // Arrange
    $token = 'valid-session-token';
    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('isSessionExpired')->andReturn(false);
    $user->shouldReceive('getSessionToken')->andReturn($token);

    // Act
    $isValid = $this->sessionManager->validateSession($user, $token);

    // Assert
    $this->assertTrue($isValid);
  }

  /** @test */
  public function rechaza_token_invalido()
  {
    // Arrange
    $validToken = 'valid-token';
    $invalidToken = 'invalid-token';

    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('isSessionExpired')->andReturn(false);
    $user->shouldReceive('getSessionToken')->andReturn($validToken);

    // Act
    $isValid = $this->sessionManager->validateSession($user, $invalidToken);

    // Assert
    $this->assertFalse($isValid);
  }

  /** @test */
  public function rechaza_sesion_expirada_en_validacion()
  {
    // Arrange
    $userId = 1;
    $token = 'test-token';

    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('isSessionExpired')->andReturn(true);
    $user->shouldReceive('getId')->andReturn($userId);

    $this->userRepository->shouldReceive('clearSession')
      ->once()
      ->with($userId);

    // Act
    $isValid = $this->sessionManager->validateSession($user, $token);

    // Assert
    $this->assertFalse($isValid);
  }

  /** @test */
  public function obtiene_informacion_de_expiracion_para_sesion_activa()
  {
    // Arrange
    $expiresAt = now()->addHours(12);

    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('hasActiveSession')->andReturn(true);
    $user->shouldReceive('getSessionExpiresAt')->andReturn($expiresAt);

    // Act
    $info = $this->sessionManager->getSessionExpirationInfo($user);

    // Assert
    $this->assertNotNull($info);
    $this->assertArrayHasKey('expires_at', $info);
    $this->assertArrayHasKey('expires_in_hours', $info);
    $this->assertArrayHasKey('expires_in_minutes', $info);
    $this->assertArrayHasKey('is_expired', $info);
    $this->assertEquals($expiresAt, $info['expires_at']);
    $this->assertFalse($info['is_expired']);
  }

  /** @test */
  public function retorna_null_para_usuario_sin_sesion_activa()
  {
    // Arrange
    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('hasActiveSession')->andReturn(false);

    // Act
    $info = $this->sessionManager->getSessionExpirationInfo($user);

    // Assert
    $this->assertNull($info);
  }

  /** @test */
  public function retorna_null_si_no_hay_fecha_de_expiracion()
  {
    // Arrange
    $user = Mockery::mock(UserEntity::class);
    $user->shouldReceive('hasActiveSession')->andReturn(true);
    $user->shouldReceive('getSessionExpiresAt')->andReturn(null);

    // Act
    $info = $this->sessionManager->getSessionExpirationInfo($user);

    // Assert
    $this->assertNull($info);
  }
}
