@section('titulo')
    Historial de Gestiones
@endsection

<x-app-layout>
    <div class="container mx-auto ">
        <div class="overflow-x-auto">
            <div class="p-4 sticky left-0">
                <h1 class="font-bold bg-white p-4 text-gray-800 text-center mb-5">Gestiones de operación {{$operacion->operacion}} de {{ucwords(strtolower($deudor->nombre))}}</h1>
                <a href="{{ route('deudor.perfil', ['deudor' => $deudorId]) }}" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Volver</a>
                <a href="{{ route('propuesta', ['operacion' => $operacion->id]) }}" class="text-white  rounded bg-green-700 px-4 py-3 hover:bg-green-800">Nueva Gestión</a>
                <!--Bajada-->
                <div class="px-14 py-4 lg:flex lg:items-center lg:justify-between text-lg
                        bg-white rounded border border-gray-200 border-1 text-gray-600 mt-4">
                    <p>Cliente: <span class="font-bold text-black">{{ \App\Models\Cliente::find($operacion->cliente_id)->nombre }}</span></p>
                    <p>Producto: <span class="font-bold text-black">{{ \App\Models\Producto::find($operacion->producto_id)->nombre }}</span></p>
                    <p>Deuda Total: <span class="font-bold text-black">${{number_format($operacion->deuda_total, 2, ',', '.')}}</span></p>                    
                    <p>Situacion actual: 
                        <span class="font-bold text-black">
                            @php
                                $ultimaPropuesta = $operacion->propuestas()->latest('updated_at')->first();
                            @endphp
                            @if($ultimaPropuesta)
                                {{ $ultimaPropuesta->estado }}
                            @else
                                Sin gestión
                            @endif</span>
                    </p>
                </div>
                @if(session('message'))
                    <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-5 p-3 ">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
                @if($propuestas->count())
                    <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded pb-4 ">
                        <thead>
                            <tr class="bg-blue-800 text-white sticky left-0">
                                <th class="px-3 py-2 bg-blue-800 sticky left-0">Monto Negociado</th>
                                <th class="px-3 py-2">Monto de Honorarios</th>
                                <th class="px-3 py-2">Monto Total</th>
                                <th class="px-3 py-2">% y Monto de Quita</th>
                                <th class="px-3 py-2">Monto de Anticipo</th>
                                <th class="px-3 py-2">Monto a Pagar</th>
                                <th class="px-3 py-2">Cant. Cuotas 1</th>
                                <th class="px-3 py-2">Monto Cuotas 1</th>
                                <th class="px-3 py-2">Cant. Cuotas 2</th>
                                <th class="px-3 py-2">Monto Cuotas 2</th>
                                <th class="px-3 py-2">Estado de Gestión</th>
                                <th class="px-3 py-2">Más información</th>
                            </tr>
                        </thead>
                        <tbody>
                            @push('scripts')
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Livewire.on('modalInformacionPropuesta', function (data) {
                                        const propuesta = data.propuesta;
                                        const usuario = data.usuario;
                                        function formatFecha(fecha) {
                                            const opciones = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                                            const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES', opciones);
                                            return fechaFormateada;
                                        }
                                        function obtenerDescripcionTipoPropuesta(tipo) {
                                            switch (tipo) {
                                                case 1:
                                                    return 'Cancelación';
                                                case 2:
                                                    return 'Cuotas con Descuento';
                                                case 3:
                                                    return 'Cuotas Fijas con o sin Anticipo';
                                                case 4:
                                                    return 'Cuotas Variables con o sin Anticipo';
                                                default:
                                                    return 'Desconocido';
                                            }
                                        }
                                        console.log(propuesta)
                                        Swal.fire({
                                            title: 'Propuesta: Detalles',
                                            icon: 'info',
                                            html: `
                                                <p class="p-2">Usuario: <span class="font-bold text-black">${usuario.name} ${usuario.apellido}</span></p>
                                                <p class="p-2">Fecha: <span class="font-bold text-black">${formatFecha(propuesta.created_at)}</span></p>
                                                <p class="p-2">Vto. de Propuesta: <span class="font-bold text-black">${formatFecha(propuesta.vencimiento)}</span></p>
                                                <p class="p-2">Acción: <span class="font-bold text-black">${propuesta.accion}</span></p>
                                                <p class="p-2">Obs: <span class="font-bold text-black">${propuesta.observaciones}</span></p>
                                                <p class="p-2">Tipo Propuesta: <span class="font-bold text-black">${obtenerDescripcionTipoPropuesta(propuesta.tipo_de_propuesta)}</span></p>
                                            `,
                                            showCloseButton: true,
                                            showCancelButton: true,
                                            confirmButtonText: 'Nueva',
                                            confirmButtonColor: '#15803D',
                                            cancelButtonText: 'Cancelar',
                                            cancelButtonColor: '#DC2626',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = "{{ route('propuesta', ['operacion' => $operacion->id]) }}";
                                            } 
                                        });
                                    });
                                </script>
                            @endpush
                            @foreach($propuestas as $index => $propuesta)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    <td class="border p-3 text-center sticky left-0
                                    {{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                        ${{number_format($propuesta->monto_negociado, 2, ',', '.')}}
                                    </td>
                                    
                                    <td class="border p-3 text-center align-middle">
                                        ${{number_format($propuesta->honorarios, 2, ',', '.')}}
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        ${{number_format($propuesta->monto_total, 2, ',', '.')}}
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        @if ($propuesta->monto_de_quita)
                                            ${{number_format($propuesta->monto_de_quita, 2, ',', '.')}} ({{$propuesta->porcentaje_quita}}%)
                                        @else 
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        @if($propuesta->anticipo)
                                            ${{number_format($propuesta->anticipo, 2, ',', '.')}}
                                        @else 
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        @if($propuesta->monto_a_pagar)
                                            ${{number_format($propuesta->monto_a_pagar, 2, ',', '.')}}
                                        @else 
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        @if($propuesta->cantidad_de_cuotas_uno)
                                            {{$propuesta->cantidad_de_cuotas_uno}}
                                        @else 
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        @if($propuesta->monto_de_cuotas_uno)
                                            ${{number_format($propuesta->monto_de_cuotas_uno, 2, ',', '.')}}
                                        @else 
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        @if($propuesta->cantidad_de_cuotas_dos)
                                            {{$propuesta->cantidad_de_cuotas_dos}}
                                        @else 
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        @if($propuesta->monto_de_cuotas_dos)
                                            ${{number_format($propuesta->monto_de_cuotas_dos, 2, ',', '.')}}
                                        @else 
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center align-middle">
                                        {{$propuesta->estado === 'Propuesta de Pago' ? 'P. de Pago'
                                        : ($propuesta->estado === 'Acuerdo de Pago' ? 'A. de Pago'
                                        : $propuesta->estado)}}
                                    </td>

                                    <livewire:mas-informacion :propuesta="$propuesta"/>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-800 p-2 text-center font-bold">
                        Esta operación no tiene gestiones realizadas
                    </p>
                @endif
        </div>
    </div>
</x-app-layout>