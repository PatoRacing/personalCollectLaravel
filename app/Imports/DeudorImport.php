<?php

namespace App\Imports;

use App\Models\Deudor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport as AfterImportEvent;

class DeudorImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $errors = [];
    public $deudores = [];
    public $existeNroDoc = [];
    public $creados = 0;
    public $omitidosExcel = 0;
    public $omitidosDB = 0;

    public function model(array $row)
    {
        $usuarioId = auth()->user()->id;

        //Si el nro_doc ya existe en el arreglo se omite
        $nro_doc = $row['nro_doc'];
        if(in_array($nro_doc, $this->existeNroDoc)){
            $this->omitidosExcel++;
            return null;
        }

        //Si el deudor ya existe en BD se omite
        $deudor = Deudor::firstOrNew(['nro_doc' => $nro_doc]);
        if ($deudor->exists) {
            $this->omitidosDB++;
            return null;
        }

        //Si el deudor no existe en la BD ni en el arreglo se crea una nueva instancia
        $deudor = new Deudor([
        'nombre' => $row['nombre'],
        'tipo_doc' => $row['tipo_doc'],
        'nro_doc' => $nro_doc,
        'domicilio' => $row['domicilio'],
        'localidad' => $row['localidad'],
        'codigo_postal' => $row['codigo_postal'],
        'telefono' => $row['telefono'],
        'usuario_ultima_modificacion_id'=>$usuarioId
        ]);

        //Las nuevas instancias se almacenan en el arreglo
        $this->existeNroDoc[] = $nro_doc;
        $this->deudores[] = $deudor;
        $this->creados++;
        return $deudor;
    }
}
