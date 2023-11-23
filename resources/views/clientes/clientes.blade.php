@section('titulo')
    Clientes
@endsection

<x-app-layout>
        <div class="container mx-auto ">
            <div class="overflow-x-auto">
                <div class="p-4 sticky left-0">
                    <h1 class="font-extrabold text-2xl bg-white p-4 text-gray-900 text-center mb-5">Clientes</h1>
                    <a href="{{route('crear.cliente')}}" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Añadir</a>
                    @if(session('message'))
                        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3 ">
                            {{ session('message') }}
                        </div>
                    @endif                
                </div>
                @if($clientes->count())
                    <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded">
                        <thead>
                            <tr class="bg-blue-800 text-white font-bold ">
                                <th class="px-4 py-2 bg-blue-800 sticky left-0">Nombre</th>
                                <th class="px-4 py-2">Contacto</th>
                                <th class="px-4 py-2">Teléfono</th>
                                <th class="px-4 py-2">Estado</th>
                                <th class="px-4 py-2">Ult. Modificación</th>
                                <th class="px-4 py-2">Fecha Ult. Modif.</th>
                                <th class="px-4 py-2">Acción</th>
                                <th class="px-4 py-2">Usuario Ult. Importación.</th>
                                <th class="px-4 py-2">Ult. Importación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @push('scripts')
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Livewire.on('modalCliente', function (cliente) {
                                        console.log(cliente)
                                        Swal.fire({
                                            title: `${cliente.nombre}`,
                                            icon: 'info',
                                            html: `
                                                <p class="p-2">Email: <span class="font-bold text-black">${cliente.email}</span></p>
                                                <p class="p-2">Localidad: <span class="font-bold text-black">${cliente.localidad}</span></p>
                                                <p class="p-2">Código Postal: <span class="font-bold text-black">${cliente.codigo_postal}</span></p>
                                                <p class="p-2">Provincia: <span class="font-bold text-black">${cliente.provincia}</span></p>
                                                <p class="p-2">Creado: <span class="font-bold text-black">${cliente.creado}</span></p>
                                            `,
                                            showCloseButton: true,
                                            showDenyButton: true,
                                            showCancelButton: true,
                                            confirmButtonText: 'Cartera',
                                            denyButtonText: 'Actualizar',
                                            denyButtonColor: '#15803D',
                                            cancelButtonText: 'Cancelar',
                                            cancelButtonColor: '#DC2626',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Usar el usuarioId pasado desde Livewire
                                                window.location.href = "{{ route('perfil.cliente', '') }}" + '/' + cliente.id;
                                            } else if (result.isDenied) {
                                                window.location.href = "{{ route('actualizar.cliente', '') }}" + '/' + cliente.id;
                                            }
                                        });
                                    });
                                </script>
                            @endpush
                            @foreach($clientes as $index => $cliente)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    
                                    <livewire:cliente-nombre :cliente="$cliente" :index="$index" />
                                    

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ $cliente->contacto }}
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ $cliente->telefono }}
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        <livewire:actualizar-estado-cliente :cliente="$cliente"/>
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ \App\Models\User::find($cliente->usuario_ultima_modificacion_id)->name }}
                                        {{ \App\Models\User::find($cliente->usuario_ultima_modificacion_id)->apellido }}
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ \Carbon\Carbon::parse($cliente->fecha_ultima_modificacion)->format('d-m-Y / H:i:s') }}
                                    </td>
                                    
                                    <td class="border px-4 py-2 text-center align-middle">
                                        <a href="{{ route('importar.cartera.cliente', ['cliente' => $cliente->id]) }}"
                                            class=" text-white w-28 h-10 rounded bg-indigo-500 px-4 py-3 hover:bg-indigo-800">
                                            Importar
                                        </a>
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        @if (($ultimaImportacion = \App\Models\Operacion::where('cliente_id', $cliente->id)->latest('updated_at')->first()))
                                            {{ \App\Models\User::find($ultimaImportacion->usuario_ultima_modificacion_id)->name }}
                                            {{ \App\Models\User::find($ultimaImportacion->usuario_ultima_modificacion_id)->apellido }}
                                        @else
                                            Sin importaciones
                                        @endif
                                    </td>
                                    

                                    <td class="border px-4 py-2 text-center align-middle">
                                        @if ($ultimaImportacion = \App\Models\Operacion::where('cliente_id', $cliente->id)->latest('updated_at')->first())
                                            {{ \Carbon\Carbon::parse($ultimaImportacion->updated_at)->format('d-m-Y / H:i:s') }}
                                        @else
                                            Sin importaciones
                                        @endif
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-800 p-2 text-center font-bold">
                        No hay Clientes aún
                    </p>
                @endif
            </div>
        </div>
</x-app-layout>