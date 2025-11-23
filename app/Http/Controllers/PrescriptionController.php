<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\ServiciosTecnicos\DataBase;

class PrescriptionController extends Controller
{

  public function __construct(DataBase $dataBase){
    $this->dataBase = $dataBase;
  }
  /**
   * Show the prescription upload step 1 (upload image)
   */
  public function uploadStep1()
  {
    $sucursales = $this->dataBase->obtenerSucursales();
    return view('prescription.upload-step1', compact('sucursales'));
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
