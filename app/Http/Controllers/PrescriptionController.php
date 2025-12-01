<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BaseDatos;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PedidoRepository;
use App\Models\Medicamento;
use App\Repositories\SucursalRepository;
use App\Repositories\MedicamentoRepository;


class PrescriptionController extends Controller
{
  private $dataBase;
  private $pedidoRepository;
  private $sucursalRepository;
  private $medicamentoRepository;

  public function __construct(
    BaseDatos $dataBase,
    PedidoRepository $pedidoRepository,
    SucursalRepository $sucursalRepository,
    MedicamentoRepository $medicamentoRepository
  ) {
    $this->dataBase = $dataBase;
    $this->pedidoRepository = $pedidoRepository;
    $this->sucursalRepository = $sucursalRepository;
    $this->medicamentoRepository = $medicamentoRepository;
  }
  /**
   * Show the prescription upload step 1 (TODO: upload image)
   */
  public function uploadStep1()
  {
    // Only load cadenas for the initial page; branches will be requested on demand
    $cadenas = $this->dataBase->getCadenas();
    $pedidoInicial = null; // No existing prescription data for new forms
    return view('prescription.upload-step1', compact('cadenas', 'pedidoInicial'));
  }

  /**
   * Return sucursales for a given cadena (AJAX)
   */
  public function sucursalesPorCadena($cadena_id)
  {
    $sucursales = $this->dataBase->getSucursalesPorCadena($cadena_id);
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

    // Fetch sucursal details using repository
    $sucursal = $this->sucursalRepository->findSucursal($data['cadena_id'], $data['sucursal_id']);

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
      'medications.*.name' => 'required|string|max:255',
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

  /**
   * Search for medications by name
   */
  public function searchMedications(Request $request)
  {
    $query = $request->input('query');

    if (strlen($query) < 2) {
      return response()->json([]);
    }

    $medications = $this->medicamentoRepository->searchByName($query);

    return response()->json($medications);
  }
  /**
   * Get all pharmacies data for the map (AJAX)
   */
  public function getPharmaciesData()
  {
    $sucursales = $this->sucursalRepository->getAllSucursalesWithRelations();

    $data = $sucursales->map(function ($sucursal) {
      return [
        'id' => $sucursal->cadena_id . '_' . $sucursal->sucursal_id, // Unique ID for frontend
        'sucursalId' => $sucursal->sucursal_id,
        'cadenaId' => $sucursal->cadena_id,
        'name' => $sucursal->cadena->nombre . ' - ' . $sucursal->nombre,
        'address' => $sucursal->direccion,
        'distance' => 'N/A',
        'status' => $this->determineStatus($sucursal->horarios),
        'statusClass' => $this->determineStatus($sucursal->horarios) === 'Open' ? 'success' : 'danger',
        'closeTime' => $this->determineCloseTime($sucursal->horarios),
        'lat' => (float) $sucursal->latitud,
        'lng' => (float) $sucursal->longitud,
        'stock' => true, // TODO: Placeholder, would need real inventory logic
        'phone' => $sucursal->contacto ?? 'No contact info',
        'is24Hours' => false, // TODO: Derived from hours
        'hours' => $this->formatHours($sucursal->horarios),
      ];
    });

    return response()->json($data);
  }

  private function determineStatus($horarios)
  {
    // Simplified logic: Check current time against today's schedule
    $now = now();
    $dayOfWeek = $now->dayOfWeek; // 0 (Sun) - 6 (Sat)
    $time = $now->format('H:i:s');

    $schedule = $horarios->firstWhere('dia_semana', $dayOfWeek);

    if (!$schedule || $schedule->es_cerrado) {
      return 'Closed';
    }

    if ($schedule->hora_apertura && $schedule->hora_cierre) {
      if ($time >= $schedule->hora_apertura && $time <= $schedule->hora_cierre) {
        return 'Open';
      }
    }

    return 'Closed';
  }

  private function determineCloseTime($horarios)
  {
    $now = now();
    $dayOfWeek = $now->dayOfWeek;
    $schedule = $horarios->firstWhere('dia_semana', $dayOfWeek);

    if (!$schedule || $schedule->es_cerrado) {
      return 'Closed Today';
    }

    if ($schedule->hora_apertura && $schedule->hora_cierre) {
      return 'Closes at ' . \Carbon\Carbon::parse($schedule->hora_cierre)->format('g:i A');
    }

    return 'Open 24 Hours'; // Assuming null/null means 24h if not closed
  }

  private function formatHours($horarios)
  {
    $today = $horarios->firstWhere('dia_semana', now()->dayOfWeek);
    $weekdays = $horarios->whereBetween('dia_semana', [1, 5]);
    $weekend = $horarios->whereIn('dia_semana', [0, 6]);

    return [
      'today' => $this->formatDaySchedule($today),
      'weekday' => $this->formatAggregatedSchedule($weekdays),
      'weekend' => $this->formatAggregatedSchedule($weekend),
    ];
  }

  private function formatDaySchedule($schedule)
  {
    if (!$schedule || $schedule->es_cerrado)
      return 'Closed';
    if (!$schedule->hora_apertura && !$schedule->hora_cierre)
      return '24 Hours';
    return \Carbon\Carbon::parse($schedule->hora_apertura)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($schedule->hora_cierre)->format('g:i A');
  }

  private function formatAggregatedSchedule($schedules)
  {
    // Simplified: take the first one found
    $first = $schedules->first();
    return $this->formatDaySchedule($first);
  }
}
