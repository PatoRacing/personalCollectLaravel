@section('titulo')
    Cartera
@endsection

<x-app-layout>
    <div class="container mx-auto ">
        <div class="overflow-x-auto">
            <div class="p-4 sticky left-0">
                <h1 class="font-extrabold text-2xl bg-white p-4 text-gray-900 text-center mb-5">Cartera</h1>
                @if(session('message'))
                    <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3 ">
                        {{ session('message') }}
                    </div>
                @endif                
            </div>
            <div class="px-14 py-4 mb-2 lg:flex lg:items-center lg:justify-between text-lg
                        bg-white rounded border border-gray-200 border-1 text-gray-600">
                <p>Casos Activos: <span class="font-bold text-black">{{$casosActivos}}</span></p>
                <p>Total DNI: <span class="font-bold text-black">{{$totalDNI}}</span></p>
                <p>Deuda Total Activa: <span class="font-bold text-black">${{number_format($deudaActiva, 2, ',', '.')}}</span></p>               
            </div>
            @if($operaciones->count())
                <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded">
                    <thead>
                        <tr class="bg-blue-800 text-white font-bold sticky-left-0">
                            <th class="px-4 py-2 bg-blue-800 sticky left-0">Nombre</th>
                            <th class="px-4 py-2">Cliente</th>
                            <th class="px-4 py-2">Operacion</th>
                            <th class="px-4 py-2">Segmento</th>
                            <th class="px-4 py-2">Producto</th>
                            <th class="px-4 py-2">DNI</th>
                            <th class="px-4 py-2">Fecha Atraso</th>
                            <th class="px-4 py-2">Situación</th>
                            <th class="px-4 py-2">Deuda Total</th>
                            <th class="px-4 py-2">Deuda Capital</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($operaciones as $index => $operacion)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                <td class="font-bold border text-blue-800 hover:text-blue-900 px-4 py-4 text-center sticky left-0
                                    {{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    @php
                                        $nombreDeudor = ucwords(strtolower($operacion->deudorId->nombre));
                                        $nombreAcortado = (strlen($nombreDeudor) > 14) ? substr($nombreDeudor, 0, 14) . '...' : $nombreDeudor;
                                        $nombreFinal = is_numeric($nombreAcortado) ? '...' : $nombreAcortado;
                                    @endphp
                                    <a href="{{ route('deudor.perfil', ['deudor' => $operacion->deudor_id]) }}">{{ $nombreFinal }}</a>
                                </td>

                                
                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ \App\Models\Cliente::find($operacion->cliente_id)->nombre }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ $operacion->operacion }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ $operacion->segmento }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ $operacion->productoId->nombre }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ $operacion->nro_doc }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ $operacion->fecha_atraso }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    @php
                                        $ultimaPropuesta = $operacion->propuestas()->latest('updated_at')->first();
                                    @endphp
                                    @if($ultimaPropuesta && $ultimaPropuesta->estado == 'Propuesta de Pago')
                                        <a href="{{ route('deudor.perfil', ['deudor' => $operacion->deudor_id]) }}"
                                            class="bg-blue-800 hover:bg-blue-950 text-white py-3 px-2 rounded">Propuesta</a>
                                    @elseif($ultimaPropuesta && $ultimaPropuesta->estado == 'Acuerdo de Pago')
                                        <a href="{{ route('deudor.perfil', ['deudor' => $operacion->deudor_id]) }}"
                                            class="bg-green-700 hover:bg-green-800 text-white py-3 px-2 rounded">Acuerdo</a>
                                    @elseif($ultimaPropuesta && $ultimaPropuesta->estado == 'Negociación')
                                        <a href="{{ route('deudor.perfil', ['deudor' => $operacion->deudor_id]) }}"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-2 rounded">Negociación</a>
                                    @elseif(!$ultimaPropuesta)
                                        <a href="{{ route('deudor.perfil', ['deudor' => $operacion->deudor_id]) }}"
                                            class="text-black hover:bg-gray-200 py-3 px-2 rounded">Sin Gestión</a>
                                    @elseif($ultimaPropuesta && $ultimaPropuesta->estado == 'Incobrable')
                                    <a href="{{ route('deudor.perfil', ['deudor' => $operacion->deudor_id]) }}"
                                        class="bg-red-600 hover:bg-red-700 text-white py-3 px-2 rounded">Incobrable</a>
                                    @endif
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    ${{ number_format($operacion->deuda_total, 2, ',', '.') }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    ${{ number_format($operacion->deuda_capital, 2, ',', '.') }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-800 p-2 text-center font-bold">
                    No hay Cartera aún
                </p>
            @endif
        </div>
    </div>
</x-app-layout>