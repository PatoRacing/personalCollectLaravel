@section('titulo')
    Usuarios
@endsection

<x-app-layout>
    <div class="container mx-auto ">
        <div class="overflow-x-auto">
            <div class="p-4 sticky left-0">
                <h1 class="font-extrabold text-2xl bg-white p-4 text-gray-900 text-center mb-5">Usuarios</h1>
                <a href="{{route('register')}}" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Añadir</a>
                @if(session('message'))
                    <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3 ">
                        {{ session('message') }}
                    </div>
                @endif                
            </div>
            <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-blue-800 text-white font-bold">
                        <th class="px-4 py-2 bg-blue-800 sticky left-0">Nombre</th>
                        <th class="px-4 py-2">Telefono</th>
                        <th class="px-4 py-2">DNI</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Ult. Modificación</th>
                    </tr>
                </thead>
                <tbody>
                    @push('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            Livewire.on('clienteNombre', function (usuario) {
                                console.log(usuario)
                                Swal.fire({
                                    title: `${usuario.name} ${usuario.apellido}`,
                                    icon: 'info',
                                    html: `
                                        <p class="p-2">Teléfono: <span class="font-bold text-black">${usuario.telefono}</span></p>
                                        <p class="p-2">DNI: <span class="font-bold text-black">${usuario.dni}</span></p>
                                        <p class="p-2">Ingreso: <span class="font-bold text-black">${usuario.fecha_de_ingreso}</span></p>
                                        <p class="p-2">Email: <span class="font-bold text-black">${usuario.email}</span></p>
                                        <p class="p-2">Domicilio: <span class="font-bold text-black">${usuario.domicilio}</span></p>
                                        <p class="p-2">Localidad: <span class="font-bold text-black">${usuario.localidad}</span></p>
                                    `,
                                    showCloseButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: 'Actualizar',
                                    confirmButtonColor: '#15803D',
                                    cancelButtonText: 'Cancelar',
                                    cancelButtonColor: '#DC2626',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Usar el usuarioId pasado desde Livewire
                                        window.location.href = "{{ route('actualizar.usuario', '') }}" + '/' + usuario.id;
                                    }
                                });
                            });
                        </script>
                        
                        
                    @endpush
                    @foreach($usuarios as $index => $usuario)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">

                            <livewire:usuario-modal :usuario="$usuario" :index="$index" />
                            
                            <td class="border px-4 py-2 text-center align-middle">
                                {{ $usuario->telefono }}
                            </td>

                            <td class="border px-4 py-2 text-center align-middle">
                                {{ $usuario->dni }}
                            </td>

                            <td class="border px-4 py-2 text-center align-middle">
                                @if ($usuario->rol_id === 1)
                                            Agente
                                    @else 
                                            Administrador
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-center align-middle">
                                <livewire:actualizar-estado :usuario="$usuario"  />
                            </td>

                            <td class="border px-4 py-2 text-center align-middle">
                                @if($usuario->usuario_ultima_modificacion)
                                    <p>
                                        {{ \App\Models\User::find($usuario->usuario_ultima_modificacion)->name }}
                                        {{ \App\Models\User::find($usuario->usuario_ultima_modificacion)->apellido }}
                                    </p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

