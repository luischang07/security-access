<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  protected function setUp(): void
  {
    parent::setUp();

    // Configuración común para todos los tests
    config(['app.debug' => true]);
    config(['app.env' => 'testing']);

    // Configurar Mailtrap para testing (sin token real)
    config(['services.mailtrap.token' => null]);
    config(['mail.mailer' => 'array']); // Usar driver array para testing
  }
}
