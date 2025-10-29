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
    if (!User::where('email', 'demo@example.com')->exists()) {
      User::factory()->demo()->create();
    }

    if (!User::where('email', 'twice@jypetwiceinfo.com')->exists()) {
      User::factory()->create([
        'name' => 'Twice',
        'email' => 'twice@jypetwiceinfo.com',
        'nip' => Hash::make('NipSeguro123!'),
        'session_token' => null,
        'last_login_at' => null,
      ]);
    }
  }
}
