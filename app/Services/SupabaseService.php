<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
  private string $url;
  private string $anonKey;
  private string $serviceKey;

  public function __construct()
  {
    $this->url = config('services.supabase.url');
    $this->anonKey = config('services.supabase.anon_key');
    $this->serviceKey = config('services.supabase.service_key');
  }

  /**
   * Realizar consulta GET a Supabase
   */
  public function select(string $table, array $filters = [], array $select = ['*'])
  {
    $query = implode(',', $select);
    $url = "{$this->url}/rest/v1/{$table}?select={$query}";

    foreach ($filters as $key => $value) {
      $url .= "&{$key}=eq.{$value}";
    }

    $response = Http::withHeaders([
      'apikey' => $this->anonKey,
      'Authorization' => "Bearer {$this->anonKey}",
      'Content-Type' => 'application/json',
      'Prefer' => 'return=representation'
    ])->get($url);

    if ($response->successful()) {
      return $response->json();
    }

    Log::error('Supabase SELECT error', [
      'url' => $url,
      'status' => $response->status(),
      'body' => $response->body()
    ]);

    return null;
  }

  /**
   * Insertar datos en Supabase
   */
  public function insert(string $table, array $data)
  {
    $url = "{$this->url}/rest/v1/{$table}";

    $response = Http::withHeaders([
      'apikey' => $this->anonKey,
      'Authorization' => "Bearer {$this->anonKey}",
      'Content-Type' => 'application/json',
      'Prefer' => 'return=representation'
    ])->post($url, $data);

    if ($response->successful()) {
      return $response->json();
    }

    Log::error('Supabase INSERT error', [
      'table' => $table,
      'data' => $data,
      'status' => $response->status(),
      'body' => $response->body()
    ]);

    return null;
  }

  /**
   * Actualizar datos en Supabase
   */
  public function update(string $table, array $filters, array $data)
  {
    $url = "{$this->url}/rest/v1/{$table}";

    foreach ($filters as $key => $value) {
      $url .= (strpos($url, '?') === false ? '?' : '&') . "{$key}=eq.{$value}";
    }

    $response = Http::withHeaders([
      'apikey' => $this->anonKey,
      'Authorization' => "Bearer {$this->anonKey}",
      'Content-Type' => 'application/json',
      'Prefer' => 'return=representation'
    ])->patch($url, $data);

    if ($response->successful()) {
      return $response->json();
    }

    Log::error('Supabase UPDATE error', [
      'table' => $table,
      'filters' => $filters,
      'data' => $data,
      'status' => $response->status(),
      'body' => $response->body()
    ]);

    return null;
  }

  /**
   * Eliminar datos de Supabase
   */
  public function delete(string $table, array $filters)
  {
    $url = "{$this->url}/rest/v1/{$table}";

    foreach ($filters as $key => $value) {
      $url .= (strpos($url, '?') === false ? '?' : '&') . "{$key}=eq.{$value}";
    }

    $response = Http::withHeaders([
      'apikey' => $this->anonKey,
      'Authorization' => "Bearer {$this->anonKey}",
      'Content-Type' => 'application/json'
    ])->delete($url);

    return $response->successful();
  }

  /**
   * Autenticación con Supabase Auth
   */
  public function signUp(string $email, string $password, array $metadata = [])
  {
    $url = "{$this->url}/auth/v1/signup";

    $response = Http::withHeaders([
      'apikey' => $this->anonKey,
      'Content-Type' => 'application/json'
    ])->post($url, [
      'email' => $email,
      'password' => $password,
      'data' => $metadata
    ]);

    return $response->json();
  }

  /**
   * Login con Supabase Auth
   */
  public function signIn(string $email, string $password)
  {
    $url = "{$this->url}/auth/v1/token?grant_type=password";

    $response = Http::withHeaders([
      'apikey' => $this->anonKey,
      'Content-Type' => 'application/json'
    ])->post($url, [
      'email' => $email,
      'password' => $password
    ]);

    return $response->json();
  }

  /**
   * Subir archivo a Supabase Storage
   */
  public function uploadFile(string $bucket, string $path, $file)
  {
    $url = "{$this->url}/storage/v1/object/{$bucket}/{$path}";

    $response = Http::withHeaders([
      'apikey' => $this->anonKey,
      'Authorization' => "Bearer {$this->serviceKey}",
    ])->attach('file', $file, $path)
      ->post($url);

    return $response->json();
  }

  /**
   * Obtener URL pública de archivo
   */
  public function getPublicUrl(string $bucket, string $path): string
  {
    return "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
  }

  /**
   * Suscribirse a cambios en tiempo real
   */
  public function subscribe(string $table, callable $callback)
  {
    // Para implementar WebSockets o Server-Sent Events
    // Esto requeriría una implementación más compleja con ReactPHP o similar
    Log::info("Suscripción a tabla {$table} configurada");
  }
}
