@section('titulo')
    Perfil del Deudor
@endsection

<x-app-layout>
    <div class="container mx-auto ">
        <div class="overflow-x-auto">
            <div class="p-2 sticky left-0">

                <div class="font-extrabold text-2xl bg-white p-4 text-gray-900 hover:text-gray-500 text-center mb-5 flex items-center justify-center space-x-8">
                    <livewire:deudor-nombre :deudor="$deudor" />

                    @if($resultadoMasReciente == 'Sin gestión')
                        <p class="font-normal text-base rounded px-4 py-2 text-white p bg-orange-400">
                            {{$resultadoMasReciente}}
                        </p>

                    @elseif ($resultadoMasReciente == 'En proceso')
                        <p class="font-normal text-base rounded px-4 py-2 text-white p bg-indigo-600">
                            {{$resultadoMasReciente}}
                        </p>

                    @elseif ($resultadoMasReciente == 'Fallecido')
                    <p class="font-normal text-base rounded px-4 py-2 text-white p bg-red-600">
                        {{$resultadoMasReciente}}
                    </p>

                    @elseif ($resultadoMasReciente == 'Ubicado')
                    <p class="font-normal text-base rounded px-4 py-2 text-white p bg-green-700">
                        {{$resultadoMasReciente}}
                    </p>
                    @endif
                </div>

                <a href="{{route('cartera')}}" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Volver</a>
                <a href="{{route('deudor.nuevo.telefono', ['deudor' => $deudor->id])}}" class="text-white bg-green-700 hover:bg-green-900 px-5 py-3 rounded ">Añadir teléfono</a>
                @if(session('message'))
                    <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3 ">
                        {{ session('message') }}
                    </div>
                @endif
                <!--Listado telefono-->
                <div class="mt-3">
                    <h2 class="text-center bg-white font-bold text-gray-600 border-y-2 p-4">Listado de teléfonos
                        <span class="text-black font-extrabold">(Referencia: {{$deudor->telefono}})</span>
                    </h2>
                </div>
                @push('scripts')
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Livewire.on('modalDeudor', function (deudor) {
                            console.log(deudor)

                            const nombreFormateado = deudor.nombre.split(' ')
                            .map(word => word.charAt(0).toUpperCase() + word.slice(1)
                            .toLowerCase()).join(' ');
                            
                            Swal.fire({
                                title: nombreFormateado,
                                icon: 'info',
                                html: `
                                    <p class="p-2">DNI: <span class="font-bold text-black">${deudor.nro_doc}</span></p>
                                    <p class="p-2">Domicilio: <span class="font-bold text-black">${deudor.domicilio}</span></p>
                                    <p class="p-2">Localidad: <span class="font-bold text-black">${deudor.localidad}</span></p>
                                    <p class="p-2">Cod. Postal: <span class="font-bold text-black">${deudor.codigo_postal}</span></p>
                                `,
                                showCloseButton: true,
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Actualizar',
                                denyButtonText: 'Historial',
                                denyButtonColor: '#15803D',
                                cancelButtonText: 'Cancelar',
                                cancelButtonColor: '#DC2626',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('deudor.nueva.gestion', '') }}" + '/' + deudor.id;
                                } else if (result.isDenied) {
                                    window.location.href = "{{ route('deudor.historial', '') }}" + '/' + deudor.id;
                                }
                            });
                        });
                    </script>
                @endpush  
            </div>
            @if($telefonos->count())
                <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded ">
                    <thead>
                        <tr class="bg-blue-800 text-white font-bold sticky left-0">
                            <th class="px-4 py-2 bg-blue-800 sticky left-0">Tipo</th>
                            <th class="px-4 py-2">Contacto</th>
                            <th class="px-4 py-2">Número</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Usuario Ult. Modif.</th>
                            <th class="px-4 py-2">Fecha Ult. Modif.</th>
                            <th class="px-4 py-2">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($telefonos as $index => $telefono)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                <td class="border px-4 py-1 text-center sticky left-0
                                {{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    {{$telefono->tipo}}
                                </td>
                                
                                <td class="border px-4 py-1 text-center align-middle">
                                    {{$telefono->contacto}}
                                </td>

                                <td class="border px-4 py-1 text-center align-middle">
                                    {{$telefono->numero}}
                                </td>

                                <td class="border px-4 py-1 text-center align-middle">
                                    <livewire:estado-telefono :telefono="$telefono"/>
                                </td>

                                <td class="border px-4 py-1 text-center align-middle">
                                    {{ \App\Models\User::find($telefono->usuario_ultima_modificacion_id)->name }}
                                    {{ \App\Models\User::find($telefono->usuario_ultima_modificacion_id)->apellido }}
                                </td>

                                <td class="border px-4 py-1 text-center align-middle">
                                    {{ \Carbon\Carbon::parse($telefono->updated_at)->format('d-m-Y / H:i:s') }}
                                </td>

                                <td class="border px-4 py-1 text-center align-middle">
                                    <a href="{{route('deudor.actualizar.telefono',['telefono' => $telefono->id])}}"
                                        class=" text-white w-28 h-10 rounded bg-indigo-500 px-4 py-2 hover:bg-indigo-800">
                                        Editar
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-800 p-2 text-center font-bold">
                    El deudor no tiene teléfonos agregados
                </p>
            @endif 
            <!--Listado Operaciones-->
            <div class="mt-3">
                <h2 class="text-center bg-white font-bold text-gray-600 border-y-2 p-4 mb-4">Listado de Operaciones</h2>
            </div>
            <!--Cambiar a incobrable-->
            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Livewire.on('confirmarIncobrable', function (operacion) {
                        Swal.fire({
                            title: `Cambiar a incobrable`,
                            icon: 'info',
                            html: `<p>Deseas cambiar el estado de la operación a incobrable?`,
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Confirmar',
                            confirmButtonColor: '#15803D',
                            cancelButtonText: 'Cancelar',
                            cancelButtonColor: '#DC2626',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ url("/propuesta-incobrable/") }}' + '/' + operacion.id;
                            }
                        });
                    });
                </script>
            @endpush
            @if($operaciones->count())
                <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded pb-4 mb-4">
                    <thead>
                        <tr class="bg-blue-800 text-white font-bold sticky left-0">
                            <th class="px-4 py-2 bg-blue-800 sticky left-0">Operación</th>
                            <th class="px-4 py-2">Cliente</th>
                            <th class="px-4 py-2">Producto</th>
                            <th class="px-4 py-2">Segmento</th>
                            <th class="px-4 py-2">Atraso</th>
                            <th class="px-4 py-2">Deuda Total</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Act. Estado</th>
                            <th class="px-4 py-2">Gestión</th>
                            <th class="px-4 py-2">Historial</th>
                            <th class="px-4 py-2">Ult. Gestión.</th>
                            <th class="px-4 py-2">Fecha Ult. Modif.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($operaciones as $index => $operacion)
                            
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                <td class="border px-4 py-4 text-center sticky left-0
                                {{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    {{$operacion->operacion}}
                                </td>
                                
                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ \App\Models\Cliente::find($operacion->cliente_id)->nombre }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{ \App\Models\Producto::find($operacion->producto_id)->nombre }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{$operacion->segmento}}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    {{$operacion->dias_atraso}} días
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    ${{ number_format($operacion->deuda_total, 2, ',', '.') }}
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    @php
                                        $ultimaPropuesta = $operacion->propuestas()->latest('updated_at')->first();
                                    @endphp
                        
                                    @if($ultimaPropuesta && $ultimaPropuesta->estado == 'Incobrable')
                                        <p class="bg-red-600 text-white px-3 py-2 rounded">Incobrable</p>
                                    @else
                                        Sin gestión
                                    @endif
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    <livewire:cobrable :operacion="$operacion"/>
                                </td>

                                <td class="border px-4 py-4 text-center align-middle">
                                    @if($ultimaPropuesta && $ultimaPropuesta->estado == 'Acuerdo de Pago')
                                        <a class="text-white rounded bg-green-700 px-4 py-3 cursor-not-allowed">
                                            Acuerdo
                                        </a>
                                    @else
                                        <a href="{{ route('propuesta', ['operacion' => $operacion->id]) }}"
                                           class="text-white  rounded bg-green-700 px-4 py-3 hover:bg-green-800">
                                            Nueva
                                        </a>
                                    @endif
                                </td>

                                <td class="border px-4 py-2 text-center align-middle">
                                    <a href="{{route('historial.propuesta',['operacion' => $operacion->id])}}"
                                        class=" text-white w-28 h-10 rounded bg-indigo-500 px-4 py-3 hover:bg-indigo-800">
                                        Ver
                                    </a>
                                </td>

                                <td class="border px-4 py-2 text-center align-middle">
                                    @php
                                        $usuarioId = optional($ultimaPropuesta)->usuario_ultima_modificacion_id;
                                        $usuario = \App\Models\User::find($usuarioId);
                                    @endphp
                        
                                    @if($usuario)
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}.{{ $usuario->apellido }}
                                    @else
                                        Sin gestión
                                    @endif
                                </td>

                                <td class="border px-4 py-2 text-center align-middle">
                                    @php
                                        $fechaUltimaModificacion = $operacion->propuestas()->latest('updated_at')->first();
                                    @endphp
                        
                                    @if($fechaUltimaModificacion)
                                        {{ \Carbon\Carbon::parse($fechaUltimaModificacion->updated_at)->format('d-m-Y / H:i:s') }}
                                    @else
                                        Sin gestión
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-800 p-2 text-center font-bold">
                    El deudor no tiene operaciones agregadas
                </p>
            @endif
        </div>
    </div>
</x-app-layout>