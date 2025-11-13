<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  /**
   * Show the admin dashboard
   */
  public function dashboard()
  {
    return view('admin.dashboard');
  }

  /**
   * Show the user management page
   */
  public function users()
  {
    return view('admin.users');
  }

  /**
   * Show the pharmacy management page
   */
  public function pharmacies()
  {
    return view('admin.pharmacies');
  }

  /**
   * Show the orders overview
   */
  public function orders()
  {
    return view('admin.orders');
  }

  /**
   * Show the penalties management
   */
  public function penalties()
  {
    return view('admin.penalties');
  }

  /**
   * Show the reports and analytics
   */
  public function reports()
  {
    return view('admin.reports');
  }
}
