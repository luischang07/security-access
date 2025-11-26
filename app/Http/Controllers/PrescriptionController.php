<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BaseDatos;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PedidoRepository;
use App\Models\Medicamento;

class PrescriptionController extends Controller
{
  private $dataBase;
  private $pedidoRepository;

  public function __construct(BaseDatos $dataBase, PedidoRepository $pedidoRepository)
  {
    $this->dataBase = $dataBase;
    $this->pedidoRepository = $pedidoRepository;
  }
  /**
   * Show the prescription upload step 1 (upload image)
   */
  public function uploadStep1()
  {
    // Only load cadenas for the initial page; branches will be requested on demand
    $cadenas = $this->dataBase->obtenerCadenas();
    return view('prescription.upload-step1', compact('cadenas'));
  }

  /**
   * Return sucursales for a given cadena (AJAX)
   */
  public function sucursalesPorCadena($cadena_id)
  {
    $sucursales = $this->dataBase->obtenerSucursalesPorCadena($cadena_id);
    info(['sucursales' => $sucursales]);
    return response()->json($sucursales);
  }

  /**
   * Show the prescription upload step 2 (confirm details)
   */
  public function uploadStep2()
  {
    $data = session('prescription_step1');

    if (!$data) {
      return redirect()->route('prescription.upload.step1');
    }

    // Fetch sucursal details using composite key
    $sucursal = \App\Models\Sucursal::where('cadena_id', $data['cadena_id'])
      ->where('sucursal_id', $data['sucursal_id'])
      ->firstOrFail();

    return view('prescription.upload-step2', compact('data', 'sucursal'));
  }

  /**
   * Store prescription step 1 data
   */
  public function storePrescriptionStep1(Request $request)
  {
    // Validate form data
    $validated = $request->validate([
      'cadena_id' => 'required|string',
      'sucursal_id' => 'required|string',
      'cedula_profesional' => 'required|string|max:50',
      'medications' => 'required|array|min:1',
      'medications.*.medication_id' => 'required|integer|exists:medicamentos,id',
      'medications.*.quantity' => 'required|integer|min:1',
      'special_instructions' => 'nullable|string|max:1000',
    ]);

    $validated['medications'] = collect($validated['medications'])->map(function ($medication) {
      $med = Medicamento::find($medication['medication_id']);
      return [
        'medication_id' => $medication['medication_id'],
        'name' => $med->nombre ?? 'Medicamento ' . $medication['medication_id'],
        'quantity' => $medication['quantity'],
      ];
    })->values()->toArray();

    // Store prescription data in session
    session([
      'prescription_step1' => $validated
    ]);

    // Redirect to step 2
    return redirect()->route('prescription.upload.step2')
      ->with('success', __('prescription.status.success'));
  }

  /**
   * Store prescription step 2 (create order)
   */
  public function storePrescriptionStep2(Request $request)
  {
    $data = session('prescription_step1');

    if (!$data) {
      return redirect()->route('prescription.upload.step1');
    }

    // Add patient ID to data
    $data['paciente_id'] = Auth::User()->user_id;

    try {
      // Create order using repository
      $this->pedidoRepository->createOrder($data, $data['medications']);

      // Clear session
      session()->forget('prescription_step1');

      return redirect()->route('patient.orders')
        ->with('success', __('prescription.status.success'));
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', __('prescription.status.error'));
    }
  }

  /**
   * Show the pharmacy selection map
   */
  public function pharmacyMap()
  {
    return view('prescription.pharmacy-map');
  }
}
