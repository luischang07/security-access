<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
  /**
   * Show the patient dashboard
   */
  public function dashboard(Request $request)
  {
    // If AJAX request, return only content
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'html' => view('patient.partials.dashboard-content')->render()
      ]);
    }

    return view('patient.dashboard');
  }

  /**
   * Show the patient orders page
   */
  public function orders(Request $request)
  {
    // If AJAX request, return only content
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'html' => view('patient.partials.orders-content')->render()
      ]);
    }

    return view('patient.orders');
  }

  /**
   * Show the patient order history
   */
  public function orderHistory()
  {
    return view('patient.order-history');
  }

  /**
   * Show the patient profile
   */
  public function profile()
  {
    return view('patient.profile');
  }

  /**
   * Show the patient penalties
   */
  public function penalties()
  {
    return view('patient.penalties');
  }

  /**
   * Show the patient help page
   */
  public function help()
  {
    return view('patient.help');
  }
}
