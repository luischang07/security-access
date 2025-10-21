<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Create a deterministic demo user (useful for local testing)
    if (!User::where('email', 'demo@example.com')->exists()) {
      User::factory()->demo()->create();
    }

    // Preserve an additional test user used previously
    if (!User::where('email', 'test@example.com')->exists()) {
      User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'nip' => Hash::make('NipSeguro123!'),
        'session_token' => null,
        'last_login_at' => null,
      ]);
    }
  }
}
