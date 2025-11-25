<?php

namespace App\Services\Modelos;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use App\Domain\DetalleLineaPedido;
use App\Domain\LineaPedido;
use App\Domain\LineaInventario;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;


class GestorDeSurtido
{
  private SucursalService $sucursalService;
  private PedidoService $pedidoService;
  private $SinStock=collect();
  public function __construct(SucursalService $sucursalService, PedidoService $pedidoService)
  {
    $this->sucursalService = $sucursalService;
    $this->pedidoService = $pedidoService;
  }

  public function surtir(Pedido $pedido)
  {
    $ldp = $pedido->getLineasPedido();
    $sucsel = $this->pedidoService->obtenerSucursal($pedido->getCadenaSeleccionada(), $pedido->getSucursalSeleccionada());

    foreach ($ldp as $lineaPedido) {
      $cantidadSurtida;
      $ldi = $this->sucursalService->getLineaInventario($sucsel->getCadenaId(), $sucsel->getSucursalId(), $lineaPedido->getMedicamentoId());

      if ($ldi->getStockDisponible() > 0) {
        $cantidadSurtida = $lineaPedido->getCantidad() - $ldi->getStockDisponible() <= 0 ? $lineaPedido->getCantidad() : $ldi->getStockDisponible();

        $lineaPedido->crearDetalleLineaPedido($ldi->getPrecioUnitario(),$cantidadSurtida,$sucsel);
      }
      if($cantidadSurtida < $lineaPedido->getCantidad()){
        $this->SinStock->push( $lineaPedido);
      }
    }
    if($this->SinStock->count()>0){
        $sucCercanas= $this->sucursalService->calculaSucCercanas($sucsel->getCadenaId(), $sucsel->getSucursalId());
        $this->CalculaFaltantes($this->SinStock,$sucCercanas);
    }
  }

  public function CalculaFaltantes($SinStock,$sucCercanas){
    
    foreach ($sucCercanas as $suc) {
      foreach($SinStock as $ldp){
        $ldi = $this->sucursalService->getLineaInventario($suc->getCadenaId(), $suc->getSucursalId(), $ldp->getMedicamentoId());
        
      }
    }
  }

}