<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PharmacyController extends Controller
{
  /**
   * Show the pharmacy dashboard
   */
  public function dashboard()
  {
    return view('pharmacy.dashboard');
  }

  /**
   * Show the pharmacy orders page
   */
  public function orders()
  {
    return view('pharmacy.orders');
  }

  /**
   * Show the pharmacy inventory management
   */
  public function inventory()
  {
    return view('pharmacy.inventory');
  }

  /**
   * Show the pharmacy reports
   */
  public function reports()
  {
    return view('pharmacy.reports');
  }
}
