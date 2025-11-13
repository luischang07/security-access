<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
  /**
   * Show the prescription upload step 1 (upload image)
   */
  public function uploadStep1()
  {
    return view('prescription.upload-step1');
  }

  /**
   * Show the prescription upload step 2 (confirm details)
   */
  public function uploadStep2()
  {
    return view('prescription.upload-step2');
  }

  /**
   * Show the pharmacy selection map
   */
  public function pharmacyMap()
  {
    return view('prescription.pharmacy-map');
  }
}
