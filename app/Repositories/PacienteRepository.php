<?php

namespace App\Repositories;

use App\Models\Paciente;
use Illuminate\Support\Facades\DB;

class PacienteRepository
{
  /**
   * Create a new patient record
   *
   * @param int $userId
   * @return Paciente
   */
  public function create(int $userId): Paciente
  {
    return Paciente::create([
      'user_id' => $userId,
      'monto_penalizacion' => 0.00,
    ]);
  }

  /**
   * Get total count of registered patients
   *
   * @return int
   */
  public function getTotalCount(): int
  {
    return Paciente::count();
  }

  /**
   * Get count of patients registered in a date range
   *
   * @param \DateTime $startDate
   * @param \DateTime $endDate
   * @return int
   */
  public function getCountByDateRange(\DateTime $startDate, \DateTime $endDate): int
  {
    return Paciente::whereHas('user', function ($query) use ($startDate, $endDate) {
      $query->whereBetween('users.created_at', [$startDate, $endDate]);
    })->count();
  }

  /**
   * Get patient growth percentage
   *
   * @return float
   */
  public function getGrowthPercentage(): float
  {
    $now = now();
    $currentMonth = Paciente::whereHas('user', function ($query) use ($now) {
      $query->whereYear('users.created_at', $now->year)
        ->whereMonth('users.created_at', $now->month);
    })->count();

    $lastMonth = Paciente::whereHas('user', function ($query) use ($now) {
      $query->whereYear('users.created_at', $now->subMonth()->year)
        ->whereMonth('users.created_at', $now->month);
    })->count();

    if ($lastMonth === 0) {
      return $currentMonth > 0 ? 100.0 : 0.0;
    }

    return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
  }

  /**
   * Get new registrations for last N days
   *
   * @param int $days
   * @return array
   */
  public function getNewRegistrationsLastDays(int $days = 30): array
  {
    $startDate = now()->subDays($days);

    return Paciente::whereHas('user', function ($query) use ($startDate) {
      $query->where('users.created_at', '>=', $startDate);
    })
      ->join('users', 'pacientes.user_id', '=', 'users.user_id')
      ->selectRaw('DATE(users.created_at) as date, COUNT(*) as count')
      ->groupBy('date')
      ->orderBy('date')
      ->get()
      ->toArray();
  }
}
