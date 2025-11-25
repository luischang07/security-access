<?php 

namespace App\Repositories;
use App\Models\Sucursal;
use App\Models\Inventario;
use App\Domain\LineaInventario;

use Illuminate\Support\Collection;
class BaseDatos{

    public function obtenerTodasSucursales(){
        $sucursales = Sucursal::all();
        return $sucursales;
    }
    public function obtenerSucursal($cadena_id,$sucursal_id){
        $sucursal = Sucursal::first()->where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id);

        $sucursal = Sucursal::crear($sucursal);
        return $sucursal;
    }

    public function obtenerInventario($cadena_id,$sucursal_id,$medId){
        $data=Inventario::first()->where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id)->where('medicamento_id',$medId);

        return new LineaInventario($data->cadena_id, $data->sucursal_id, $data->medicamento_id, $data->stock_disponible,$data->precio_unitario);
    }

    public function obtenerSucursalesOrdenadas($cadena_id, $sucursal_id){
        // 1) Obtener la sucursal base
        $base = Sucursal::where('cadena_id', $cadena_id)
            ->where('sucursal_id', $sucursal_id)
            ->firstOrFail();

        $lat = $base->latitud;
        $lng = $base->longitud;

        // 2) Consultar TODAS las sucursales ordenadas por distancia
        $sucursales = Sucursal::select('*')
            ->selectRaw("
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitud)) *
                    cos(radians(longitud) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitud))
                )) AS distancia
            ", [$lat, $lng, $lat])
            ->whereNot(function ($q) use ($cadena_id, $sucursal_id) {
                $q->where('cadena_id', $cadena_id)
                ->where('sucursal_id', $sucursal_id);
            })
            ->orderBy('distancia', 'ASC')
            ->get()
            ->toArray(); 
        
        $collectionSucursales= collect();

        foreach($sucursales as $sucursal){
            $collectionSucursales->push(Sucursal::crear($sucursal));
        }

        return $collectionSucursales;
    }
}
