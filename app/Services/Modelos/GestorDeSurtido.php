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
  private $SinStock;
  public function __construct(SucursalService $sucursalService, PedidoService $pedidoService)
  {
    $this->sucursalService = $sucursalService;
    $this->pedidoService = $pedidoService;
    $this->SinStock=collect();
  }

  public function surtir(Pedido $pedido)
  {

    $pedido->agregarMedicamento(1, 1);
    $pedido->agregarMedicamento(2, 1);
    $pedido->agregarMedicamento(3, 1);

    $pedido->asociarSucursalAPedido("CAD001","SUC001");

    $sucsel = $this->pedidoService->obtenerSucursal($pedido->getCadenaSeleccionada(), $pedido->getSucursalSeleccionada());

    $ldp = $pedido->getLineasPedidos();

    foreach ($ldp as $lineaPedido) {
      $cantidadSurtida = 0;
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
      $this->CalculaFaltantes($this->SinStock,$sucCercanas, $pedido);
    }
    //info("pedido", [$pedido->getLineasPedido()->getDetalleLineaPedido()->getSucursal()->getSucursalId()]);
    //info("sucursales", [$sucCercanas]);
  }

  public function CalculaFaltantes($SinStock,$sucCercanas, $pedido){
    
    foreach ($sucCercanas as $suc) {
      foreach($SinStock as $ldp){
        $ldi = $this->sucursalService->getLineaInventario($suc->getCadenaId(), $suc->getSucursalId(), $ldp->getMedicamentoId());
        $cantFaltante= $ldp->getCantidadFaltante();
        $cantidadSurtida=0;
        if($ldi->getStockDisponible() > 0 && $cantFaltante > 0 ){
          $cantidadSurtida = $ldp->getCantidad() - $ldi->getStockDisponible() <= 0 ? $ldp->getCantidad() : $ldi->getStockDisponible();

          $ldp->crearDetalleLineaPedido($ldi->getPrecioUnitario(),$cantidadSurtida,$suc);

          info("pedido", [$pedido->getLineasPedido()->getDetalleLineaPedido()->getSucursal()->getCadenaId()]);
          if($cantidadSurtida == $cantFaltante){
            $this->SinStock = $this->SinStock->reject(function($item) use ($ldp) {
                return $item === $ldp; // Elimina si es el mismo objeto
            });
          }
        }
      }
    }
  }

}