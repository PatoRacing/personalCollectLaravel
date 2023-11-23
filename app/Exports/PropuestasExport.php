<?php

namespace App\Exports;

use App\Models\Propuesta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PropuestasExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $propuestas = Propuesta::where('estado', 'Propuesta de Pago')->get();
        $data = collect();

        // Iterar sobre cada propuesta y agregar los valores a la colección
        foreach ($propuestas as $propuesta) {
            $data->push([
                $propuesta->usuarioUltimaModificacion->name . ' ' . $propuesta->usuarioUltimaModificacion->apellido,
                $propuesta->deudorId->tipo_doc,
                $propuesta->deudorId->nro_doc,
                ucwords(strtolower($propuesta->deudorId->nombre)),
                $propuesta->operacionId->productoId->nombre,
                $propuesta->operacionId->operacion,
                '$'. number_format($propuesta->monto_negociado, 2, ',', '.'),
                'PCollect', 
                \Carbon\Carbon::parse($propuesta->vencimiento)->format('d-m-Y'),
                $propuesta->anticipo ? '$' . number_format($propuesta->anticipo, 2, ',', '.') : '-',
                $propuesta->cantidad_de_cuotas_uno . ($propuesta->cantidad_de_cuotas_dos ? "({$propuesta->cantidad_de_cuotas_dos})" : ''),
                '$' . number_format($propuesta->monto_de_cuotas_uno, 2, ',', '.') . ($propuesta->monto_de_cuotas_dos ? "($" . number_format($propuesta->monto_de_cuotas_dos, 2, ',', '.') . ")" : ''),
                '$' . number_format($propuesta->monto_total, 2, ',', '.'),
                $propuesta->porcentaje_quita ? '$' . number_format($propuesta->monto_de_quita, 2, ',', '.') . "({$propuesta->porcentaje_quita})%" : '-',
                $propuesta->operacionId->segmento,
            ]);
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Responsable',
            'Tipo',
            'N. de Doc.',
            'Ap. y nombre',
            'Producto',
            'Nro. Operación',
            'Capital',
            'Estudio',
            'Vencimiento',
            'Anticipo',
            'Nro. Cuotas',
            '$ Cuotas',
            'Total ACP',
            '% Quita',
            'Segmento',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para las celdas de la fila 1 (encabezados)
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('1')->getFill()->getStartColor()->setARGB('FFD3D3D3'); // Gris claro

        // Estilo para todas las celdas con bordes delgados y centrado
        $sheet->getStyle($sheet->calculateWorksheetDimension())
            ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($sheet->calculateWorksheetDimension())
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Personalizar el estilo de la columna 'Segmento'
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle("{$lastColumn}1:{$lastColumn}{$sheet->getHighestRow()}")
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

        // Establecer el ancho predeterminado para todas las columnas
        $sheet->getDefaultColumnDimension()->setWidth(25);

        // Establecer la altura de todas las filas al doble de la normal
        foreach ($sheet->getRowDimensions() as $dimension) {
            $dimension->setRowHeight($dimension->getRowHeight() * 2);
        }

        return [];
    }
}
