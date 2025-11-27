<?php 

namespace App\Repositories;
use App\Models\Sucursal;
use App\Models\Inventario;
use App\Domain\LineaInventario;
use App\Models\Medicamento;
use App\Domain\Medicamento as med;


use App\Domain\Sucursal as DomainSucursal;
use Illuminate\Support\Collection;
class BaseDatos{
    public function __construct(){
        
    }
    public function obtenerTodasSucursales(){
        $sucursales = Sucursal::all();
        return $sucursales;
    }
    public function obtenerSucursal($cadena_id,$sucursal_id){
        $sucursal = Sucursal::where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id)->first();
        
        $sucursal = DomainSucursal::crear($sucursal);
        return $sucursal;
    }

    public function obtenerInventario($cadena_id,$sucursal_id,$medId){
        $data=Inventario::where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id)->where('medicamento_id',$medId)->first();

        return new LineaInventario($data->cadena_id, $data->sucursal_id, $data->medicamento_id, $data->stock_disponible,$data->precio_unitario);
    }

    public function obtenerMedicamento($medId){
        $medicamento = Medicamento::where('id',$medId)->first();
        info('medicamento', [$medicamento]);
        return new med($medicamento->id, $medicamento->nombre, $medicamento->descripcion, $medicamento->unidad_medida, $medicamento->unidades);
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
            ->get();
        
        $collectionSucursales= collect();

        info('sucs', [$sucursales]);

        foreach($sucursales as $sucursal){
            $collectionSucursales->push(DomainSucursal::crear($sucursal));
        }

        return $collectionSucursales;
    }

}
