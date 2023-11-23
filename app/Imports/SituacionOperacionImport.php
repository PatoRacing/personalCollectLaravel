<?php

namespace App\Imports;

use App\Models\SituacionesOperaciones;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SituacionOperacionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $clienteId;
    public function __construct($clienteId)
    {
        $this->clienteId = $clienteId;
    }
    public function model(array $row)
    {
        $operacion = new SituacionesOperaciones([
            'operacion' => $row['operacion'],
            'cliente_id' => $this->clienteId
        ]);
        return $operacion;
    }
}
