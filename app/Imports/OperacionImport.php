<?php

namespace App\Imports;

use App\Models\Cliente;
use App\Models\Deudor;
use App\Models\Operacion;
use App\Models\Producto;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class OperacionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    //Obtener clienteId
    protected $clienteId;
    protected $cliente;
    protected $operaciones;
    protected $error;
    protected $operacionesActualizadas = 0;
    protected $operacionesCreadas = 0;
    public function __construct($clienteId, $operaciones, $cliente)
    {
        $this->clienteId = $clienteId;
        $this->operaciones = $operaciones;
        $this->cliente = $cliente;
    }
    
    public function getError()
    {
        return $this->error;
    }
    public function getOperacionesActualizadas()
    {
        return $this->operacionesActualizadas;
    }

    public function getOperacionesCreadas()
    {
        return $this->operacionesCreadas;
    }

    public function model(array $row)
    {   
        //Obtener usuarioId
        $usuarioId = auth()->user()->id;

        //Formatear fechas
        $fechaApertura = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_apertura'])->format('d-m-Y');
        $fechaAtraso = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_atraso'])->format('d-m-Y');
        $fechaUltPago = $row['fecha_ult_pago'];
            if (!empty($fechaUltPago)) {
                $fechaUltPago = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fechaUltPago)->format('d-m-Y');
            } else {
                $fechaUltPago = null;
            }
        $fechaAsignacion = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_asignacion'])->format('d-m-Y');

        //Obtener productoId y chequear que ese cliente tenga ese producto
        $nombreProducto = $row['producto'];
        $producto = Producto::where('nombre', $nombreProducto)
                            ->where('cliente_id', $this->clienteId)
                            ->first();
        if (!$producto) {
            $nombreCliente = $this->cliente->nombre;
            $this->error = "El cliente ($nombreCliente) no tiene asignados los productos que quieres importar.";
            return null;
            }

        $productoId = $producto->id;
        //Obtener deudorId
        $nroDoc = $row['nro_doc'];
        $deudor = Deudor::where('nro_doc', $nroDoc)->first();
        $deudorId = $deudor ? $deudor->id : 1;
        
        $operacion = $this->operaciones->where('operacion', $row['operacion'])->first();
        if($operacion) {
            $operacion->segmento = $row['segmento'];
            $operacion->operacion = $row['operacion'];
            $operacion->fecha_apertura = $fechaApertura;
            $operacion->cant_cuotas = $row['cant_cuotas'];
            $operacion->sucursal = $row['sucursal'];
            $operacion->fecha_atraso = $fechaAtraso;
            $operacion->dias_atraso = $row['dias_atraso'];
            $operacion->deuda_total = $row['deuda_total'];
            $operacion->deuda_capital = $row['deuda_capital'];
            $operacion->fecha_ult_pago = $fechaUltPago;
            $operacion->estado = $row['estado'];
            $operacion->fecha_asignacion = $fechaAsignacion;
            $operacion->ciclo = $row['ciclo'];
            $operacion->situacion = 1;
            $operacion->usuario_ultima_modificacion_id = $usuarioId;
            $operacion->cliente_id = $this->clienteId;
            $operacion->producto_id = $productoId;
            $operacion->deudor_id = $deudorId;
            $operacion->usuario_asignado_id = 1;
            $operacion->save();
            $this->operacionesActualizadas++;
        } else {
            $operacion = new Operacion([
                'segmento'=>$row['segmento'],
                'operacion'=>$row['operacion'],
                'fecha_apertura'=>$fechaApertura,
                'cant_cuotas'=>$row['cant_cuotas'],
                'sucursal'=>$row['sucursal'],
                'fecha_atraso'=>$fechaAtraso,
                'dias_atraso'=>$row['dias_atraso'],
                'deuda_total'=>$row['deuda_total'],
                'deuda_capital'=>$row['deuda_capital'],
                'fecha_ult_pago'=>$fechaUltPago,
                'estado'=>$row['estado'],
                'fecha_asignacion'=>$fechaAsignacion,
                'ciclo'=>$row['ciclo'],
                'nro_doc'=>$row['nro_doc'],
                'situacion'=> 1,
                //Asignacion de id de usuario
                'usuario_ultima_modificacion_id'=>$usuarioId,
                //Asignacion de id de cliente
                'cliente_id' => $this->clienteId,
                //Asignacion de id de producto
                'producto_id'=>$productoId,
                //Asignacion de id de deudor
                'deudor_id'=>$deudorId,
                'usuario_asignado_id'=>1
            ]);
            $operacion->save();
            $this->operacionesCreadas++;
        }
        return $operacion;        
    }
}
