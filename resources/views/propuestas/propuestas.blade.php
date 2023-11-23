@section('titulo')
    Propuestas de Pago
@endsection

<x-app-layout>
    <div class="container mx-auto ">
        <div class="overflow-x-auto">
            <div class="px-4 py-2 sticky left-0">
                <h1 class="font-extrabold text-2xl bg-white p-4 text-gray-900 text-center mb-2">Propuestas de Pago</h1>
                
                <form action="{{ route('exportar.propuestas') }}" method="post">
                    @csrf
                    <button type="submit" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Exportar</button>
                </form>

                @if(session('message'))
                    <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3 ">
                        {{ session('message') }}
                    </div>
                @endif                
            </div>
            @if($propuestas->count())
                <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded">
                    <thead>
                        <tr class="bg-blue-800 text-white text-sm">
                            <th class="px-2 py-2 bg-blue-800 sticky left-0">Responsable</th>
                            <th class="px-2 py-2">Tipo</th>
                            <th class="px-2 py-2">N. de Doc.</th>
                            <th class="px-2 py-2">Ap. y nombre</th>
                            <th class="px-2 py-2">Producto</th>
                            <th class="px-2 py-2">Nro. Operación</th>
                            <th class="px-2 py-2">Capital</th>
                            <th class="px-2 py-2">Estudio</th>
                            <th class="px-2 py-2">Vencimiento</th>
                            <th class="px-2 py-2">Anticipo</th>
                            <th class="px-2 py-2">Nro. Cuotas</th>
                            <th class="px-2 py-2">$ Cuotas</th>
                            <th class="px-2 py-2">Total ACP</th>
                            <th class="px-2 py-2">% Quita</th>
                            <th class="px-2 py-2">Segmento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propuestas as $index => $propuesta)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">

                                <td class="border px-2 py-2 text-center sticky left-0
                                            {{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    {{ $propuesta->usuarioUltimaModificacion->name }}
                                    {{ $propuesta->usuarioUltimaModificacion->apellido }}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ $propuesta->deudorId->tipo_doc}}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ $propuesta->deudorId->nro_doc}}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ucwords(strtolower($propuesta->deudorId->nombre))}}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ $propuesta->operacionId->productoId->nombre}}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ $propuesta->operacionId->operacion}}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    ${{ number_format($propuesta->monto_negociado, 2, ',', '.') }}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    PCollect
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ \Carbon\Carbon::parse($propuesta->vencimiento)->format('d-m-Y') }}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    @if ($propuesta->anticipo)
                                        ${{ number_format($propuesta->anticipo, 2, ',', '.') }}
                                    @else 
                                        -
                                    @endif
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ $propuesta->cantidad_de_cuotas_uno}}
                                        @if ($propuesta->cantidad_de_cuotas_dos)
                                        ({{$propuesta->cantidad_de_cuotas_dos}})
                                        @endif
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    ${{ number_format($propuesta->monto_de_cuotas_uno, 2, ',', '.') }}
                                        @if ($propuesta->monto_de_cuotas_dos)
                                        (${{ number_format($propuesta->monto_de_cuotas_dos, 2, ',', '.') }})
                                        @endif
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    ${{ number_format($propuesta->monto_total, 2, ',', '.') }}
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    @if ($propuesta->porcentaje_quita)
                                        ${{ number_format($propuesta->monto_de_quita, 2, ',', '.') }}
                                        ({{$propuesta->porcentaje_quita}})%
                                    @else 
                                        -
                                    @endif
                                </td>

                                <td class="border px-2 py-2 text-center align-middle">
                                    {{ $propuesta->operacionId->segmento}}
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-800 p-2 text-center font-bold">
                    No hay propuestas aún
                </p>
            @endif
        </div>
    </div>
</x-app-layout>