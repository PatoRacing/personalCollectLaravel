@section('titulo')
    Perfil de Cliente
@endsection

<x-app-layout>
        <div class="container mx-auto ">
            <div class="overflow-x-auto">
                <div class="p-4 sticky left-0">
                    <h1 class="font-extrabold text-2xl bg-white p-4 text-gray-900 text-center mb-5">{{$cliente->nombre}}</h1>
                    <a href="{{route('clientes')}}" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Volver</a>
                    <a href="{{ route('importar.cartera.cliente', ['cliente' => $cliente->id]) }}" class="text-white bg-green-700 hover:bg-green-900 px-5 py-3 rounded">Importar</a>
                    @if(session('message'))
                        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3 ">
                            {!! nl2br(session('message')) !!}
                        </div>
                    @endif                
                </div>
                <div class="px-14 py-4 mb-2 lg:flex lg:items-center lg:justify-between text-lg
                            bg-white rounded border border-gray-200 border-1 text-gray-600">
                    <p>Total Casos: <span class="font-bold text-black">{{ $totalCasos }}</span></p>
                    <p>Casos Activos: <span class="font-bold text-black">{{ $casosActivos }}</span></p>
                    <p>Total DNI: <span class="font-bold text-black">{{ $totalDNI }}</span></p>
                    <p>Deuda Total (suma): <span class="font-bold text-black">${{ number_format($deudaTotal, 2, ',', '.') }}</span></p>        
                    <p>Deuda Total Activa: <span class="font-bold text-black">${{ number_format($deudaActiva, 2, ',', '.') }}</span></p>               
                </div>
                @if($operaciones->count())
                    <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded">
                        <thead>
                            <tr class="bg-blue-800 text-white font-bold sticky-left-0">
                                <th class="px-4 py-4 bg-blue-800 sticky left-0">Nombre</th>
                                <th class="px-4 py-4">Operacion</th>
                                <th class="px-4 py-4">Segmento</th>
                                <th class="px-4 py-4">Producto</th>
                                <th class="px-4 py-4">DNI</th>
                                <th class="px-4 py-4">Fecha Atraso</th>
                                <th class="px-4 py-4">Fecha Ult. Pago</th>
                                <th class="px-4 py-4">Deuda Total</th>
                                <th class="px-4 py-4">Deuda Capital</th>
                                <th class="px-4 py-4">Situación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($operaciones as $index => $operacion)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    <td class="font-bold border text-blue-800 hover:text-blue-900 px-4 py-4 text-center sticky left-0
                                    {{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                        <a href="{{ route('deudor.perfil', ['deudor' => $operacion->deudor_id]) }}">{{ ucwords(strtolower($operacion->deudorId->nombre)) }}</a>
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
                                        {{ $operacion->fecha_ult_pago }}
                                    </td>
                                    
                                    <td class="border px-4 py-4 text-center align-middle">
                                        ${{ number_format($operacion->deuda_total, 2, ',', '.') }}
                                    </td>

                                    <td class="border px-4 py-4 text-center align-middle">
                                        ${{ number_format($operacion->deuda_capital, 2, ',', '.') }}
                                    </td>

                                    <td class="border px-4 py-4 text-center align-middle">
                                        @php
                                        $bgColorClass = $operacion->situacion === 1 ? 'bg-green-700 hover:bg-green-800' : 'bg-red-600 hover:bg-red-700';
                                        @endphp
                                        <p
                                            class="{{ $bgColorClass }} text-white w-28 h-10 rounded flex items-center justify-center"
                                        >
                                            @if ($operacion->situacion === 1)
                                                    Activa
                                            @else 
                                                    Inactiva
                                            @endif
                                        </p>
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