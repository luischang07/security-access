<?php


namespace App\Domain;

use Illuminate\Support\Collection;
use App\Models\Pedido as PedidoModel;
use App\Domain\Sucursal;
use App\Domain\LineaPedido;
use App\Domain\Medicamento;
use Carbon\Carbon;

class Pedido
{
  private ?string $folio = null;
  private ?string $cedulaProfesional = null;

  private ?Carbon $fechaPedido = null;
  private ?Carbon $fechaRecoleccion = null;
  private ?string $estatus = null;
  private Collection $lineasPedido;
  private ?int $pacienteId = null;
  private ?Sucursal $sucursal = null;
  private float $costoTotal = 0;
  private Collection $faltantes;

  private Collection $ruta;
  private $routeGeometry = null;
  private $montoPenalizacion;


  private function __construct()
  {
    $this->lineasPedido = collect();
    $this->faltantes = collect();
    $this->ruta = collect();
  }

  public static function createPedido(int $pacienteId): self
  {
    $instancia = new self();

    $instancia->pacienteId = $pacienteId;
    return $instancia;
  }

  public function getTotal(): float
  {
    $total = 0.0;
    foreach ($this->lineasPedido as $linea) {
      $detalles = $linea->getDetalles();
      foreach ($detalles as $dlp) {
        $total += $dlp->getPrecio();
      }
    }
    $this->costoTotal = $total;
    return $this->costoTotal;
  }

  public function setSucursal(Sucursal $sucursal): void
  {
    $this->sucursal = $sucursal;
  }

  public function anadirARuta(Sucursal $sucursal): void
  {
    //Si ya existe la sucursal dentro de la ruta, no agregarla de nuevo
    if (
      !$this->ruta->contains(function ($suc) use ($sucursal) {
        return $suc->getCadenaId() === $sucursal->getCadenaId() &&
          $suc->getSucursalId() === $sucursal->getSucursalId();
      })
    ) {
      $this->ruta->push($sucursal);
    }
  }
  public function agregarMedicamento(int $medId, int $cantidad, ?Medicamento $medicamento): void
  {
    if (!$medId) {
      return;
    }
    if (!$this->lineasPedido instanceof Collection) {
      $this->lineasPedido = collect();
    }

    $existing = $this->lineasPedido->first(function ($ldp) use ($medId) {
      return $ldp->getMedicamentoId() === (int) $medId;
    });

    if ($existing) {
      $existing->setCantidad((int) $cantidad);
      return;
    }

    $lineaPedido = new LineaPedido($medId, $cantidad, $medicamento);
    $this->lineasPedido->push($lineaPedido);
  }

  public function eliminarMedicamento(int $medId): void
  {
    if (!$this->lineasPedido instanceof Collection) {
      return;
    }

    $this->lineasPedido = $this->lineasPedido
      ->filter(function (LineaPedido $ldp) use ($medId) {
        return $ldp->getMedicamentoId() !== (int) $medId;
      })
      ->values();
  }
  public function eliminarDetalle($dlp): void
  {
    if (!$this->lineasPedido instanceof Collection) {
      return;
    }

    $lineaPedido = $this->lineasPedido
      ->filter(function (LineaPedido $ldp) use ($dlp) {
        return $ldp->getMedicamentoId() === (int) $dlp->getMedicamentoId();
      })
      ->values();
    $lineaPedido->get(0)->eliminarDetalle($dlp);
  }
  /**
   * Devuelve la LineaPedido para el medicamento dado o null si no existe.
   */
  public function getLineaPedido(int $medId): ?LineaPedido
  {
    if (!$this->lineasPedido instanceof Collection) {
      return null;
    }

    return $this->lineasPedido->first(function ($ldp) use ($medId) {
      return $ldp->getMedicamentoId() === (int) $medId;
    });
  }

  /**
   * Return a flat collection with all DetalleLineaPedido objects across lineas.
   */
  public function getAllDetalles(): Collection
  {
    return $this->lineasPedido->flatMap(function (LineaPedido $linea) {
      return $linea->getDetalleLineaPedido();
    })->values();
  }

  public function crearDetalleLineaPedido(float $precio_unitario, int $cantidadSurtida, Sucursal $sucursal, int $medicamento_id): void
  {

    $linea = $this->lineasPedido->first(function (LineaPedido $item) use ($medicamento_id) {
      return $item->getMedicamentoId() === (int) $medicamento_id;
    });

    if ($linea) {
      $linea->crearDetalleLineaPedido($precio_unitario, $cantidadSurtida, $sucursal, $medicamento_id);
    }

    $this->lineasPedido = $this->lineasPedido->map(function (LineaPedido $item) use ($medicamento_id, $precio_unitario, $cantidadSurtida, $sucursal) {
      if ($item->getMedicamentoId() === (int) $medicamento_id) {
        $item->crearDetalleLineaPedido($precio_unitario, $cantidadSurtida, $sucursal, $medicamento_id);
      }
      return $item;
    });
  }

  public function calcularTotales(): void
  {
    $acumulado = 0;
    foreach ($this->lineasPedido as $linea) {
      $acumulado += $linea->calcularSubtotal();
    }
    $this->costoTotal = $acumulado;
  }

  public function setFaltantes(?Collection $faltantes): void
  {
    $this->faltantes = $faltantes ?? collect();
  }

  public function getFaltantes(): Collection
  {
    return $this->faltantes ?? collect();
  }

  public function tieneFaltantes(): bool
  {
    return $this->getFaltantes()->count() > 0;
  }

  public function reiniciarParaCaptura(): void
  {
    foreach ($this->lineasPedido as $linea) {
      $linea->limpiarDetalles();
    }
    $this->ruta = collect();
    $this->faltantes = collect();
    $this->costoTotal = 0;
    $this->estatus = null;
    $this->fechaRecoleccion = null;
  }

  public function asignarFechaPedido(): void
  {
    $this->fechaPedido = Carbon::now();
  }

  public function asignarFechaRecoleccion(): void
  {
    $this->fechaRecoleccion = $this->fechaPedido->copy()->addDay();
  }

  public function getLineasPedido(): ?LineaPedido
  {
    return $this->lineasPedido->get(0);
  }

  public function getLineasPedidos(): Collection
  {
    return $this->lineasPedido;
  }

  public function getSucursal(): ?Sucursal
  {
    return $this->sucursal;
  }

  public function setCedulaProfesional(string $cedula): void
  {
    $this->cedulaProfesional = $cedula;
  }

  public function getCedulaProfesional(): ?string
  {
    return $this->cedulaProfesional;
  }
  public function setMontoPenalizacion($monto)
  {
    $this->costoTotal = $this->costoTotal + $monto;
    $this->montoPenalizacion = $monto;
  }

  public function getMontoPenalizacion()
  {
    return $this->montoPenalizacion;
  }

  public function sumarMontoPenalizacion(float $monto): void
  {
    $this->costoTotal = $this->costoTotal + $monto;

    info('monto final: ', [$this->costoTotal]);
  }

  public function getFechaRecoleccion(): ?Carbon
  {
    return $this->fechaRecoleccion;
  }

  public function getEstatus(): ?string
  {
    return $this->estatus;
  }

  public function setEstatus(string $estatus): void
  {
    $this->estatus = $estatus;
  }

  public function cambiarEstatus(string $nuevoEstatus): void
  {
    $this->estatus = $nuevoEstatus;
  }

  public function getCostoTotal(): float
  {
    return $this->costoTotal;
  }

  public function getFolio(): ?string
  {
    return $this->folio;
  }

  public function asignarFolio(string $folio): void
  {
    $this->folio = $folio;
  }

  public function getPacienteId(): ?int
  {
    return $this->pacienteId;
  }
  public function getRuta(): Collection
  {
    return $this->ruta;
  }

  public function setRouteGeometry($geometry): void
  {
    $this->routeGeometry = $geometry;
  }

  public function getRouteGeometry()
  {
    return $this->routeGeometry;
  }

  public function getFechaPedido(): ?Carbon
  {
    return $this->fechaPedido;
  }

  //Crear pedido desde modelo Eloquent
  public static function crear(PedidoModel $pedidoModel): Pedido
  {
    $pedido = new self();
    $pedido->folio = $pedidoModel->folio_pedido;
    $pedido->pacienteId = $pedidoModel->paciente_id;
    $pedido->cedulaProfesional = $pedidoModel->cedula_profesional;
    $pedido->fechaPedido = $pedidoModel->fecha_pedido;
    $pedido->fechaRecoleccion = $pedidoModel->fecha_recoleccion;
    $pedido->estatus = $pedidoModel->estatus;
    $pedido->costoTotal = (float) $pedidoModel->costo_total;

    $sucursal = Sucursal::crear($pedidoModel->sucursal);
    $pedido->setSucursal($sucursal);

    $lineasPedidoCollection = collect();
    foreach ($pedidoModel->lineasPedidos as $lineaModel) {
      $medicamento = new Medicamento(
        $lineaModel->medicamento->id,
        $lineaModel->medicamento->nombre,
        $lineaModel->medicamento->descripcion,
        $lineaModel->medicamento->unidad_medida,
        (int) $lineaModel->medicamento->unidades
      );
      $lineaPedido = new LineaPedido($lineaModel->medicamento_id, $lineaModel->cantidad, $medicamento);
      $lineasPedidoCollection->push($lineaPedido);

      foreach ($lineaModel->detalles as $detalleModel) {
        $sucursalDetalle = Sucursal::crear($detalleModel->sucursal);
        $lineaPedido->crearDetalleLineaPedido(
          $detalleModel->precio_unitario,
          $detalleModel->cantidad_surtida,
          $sucursalDetalle,
          $detalleModel->medicamento_id
        );
      }
    }
    $pedido->lineasPedido = $lineasPedidoCollection;

    return $pedido;
  }

  public function calcularPorcentajeSurtido(): float
  {
    $totalSolicitado = 0;
    $totalSurtido = 0;

    foreach ($this->lineasPedido as $linea) {
      $totalSolicitado += $linea->getCantidad();
      $totalSurtido += $linea->calcularCantidadSurtida();
    }

    if ($totalSolicitado === 0) {
      return 0.0;
    }

    return $totalSurtido / $totalSolicitado;
  }

  public function removerLineasSinDetalles()
  {
    $this->lineasPedido = $this->lineasPedido->filter(function ($linea) {
      return $linea->calcularCantidadSurtida() > 0;
    })->values();
  }
}
