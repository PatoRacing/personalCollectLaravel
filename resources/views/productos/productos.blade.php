@section('titulo')
    Productos
@endsection

<x-app-layout>
        <div class="container mx-auto ">
            <div class="overflow-x-auto">
                <div class="p-4 sticky left-0">
                    <h1 class="font-extrabold text-2xl bg-white p-4 text-gray-900 text-center mb-5">Productos</h1>
                    <a href="{{route('crear.producto')}}" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Añadir</a>
                    @if(session('message'))
                        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3 ">
                            {{ session('message') }}
                        </div>
                    @endif                
                </div>
                @if($productos->count())
                    <table class="table-auto overflow-x-auto min-w-full bg-white shadow-md rounded">
                        <thead>
                            <tr class="bg-blue-800 text-white font-bold">
                                <th class="px-4 py-2 bg-blue-800 sticky left-0">Producto</th>
                                <th class="px-4 py-2">Cliente</th>
                                <th class="px-4 py-2">Honorarios</th>
                                <th class="px-4 py-2">Comision Cliente</th>
                                <th class="px-4 py-2">Estado</th>
                                <th class="px-4 py-2">Creado</th>
                                <th class="px-4 py-2">Ult. Modificación</th>
                                <th class="px-4 py-2">Fecha Ult. Modificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @push('scripts')
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Livewire.on('modalProducto', function (producto) {
                                        console.log(producto)
                                        Swal.fire({
                                            title: `${producto.nombre}`,
                                            icon: 'info',
                                            html: `
                                                <p class="p-2">Cliente: <span class="font-bold text-black">${producto.cliente_nombre}</span></p>
                                                <p class="p-2">Honorarios: <span class="font-bold text-black">${producto.honorarios}</span></p>
                                                <p class="p-2">Comisión Cliente: <span class="font-bold text-black">${producto.comision_cliente}</span></p>
                                            `,
                                            showCloseButton: true,
                                            showCancelButton: true,
                                            confirmButtonText: 'Actualizar',
                                            confirmButtonColor: '#15803D',
                                            cancelButtonText: 'Cancelar',
                                            cancelButtonColor: '#DC2626',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = "{{ route('actualizar.producto', '') }}" + '/' + producto.id; 
                                            } 
                                        });
                                    });
                                </script>
                            @endpush
                            @foreach($productos as $index => $producto)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                    
                                    <livewire:producto-nombre :producto="$producto" :index="$index" />
                                    

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ \App\Models\Cliente::find($producto->cliente_id)->nombre }}
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ $producto->honorarios}}%
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ $producto->comision_cliente}}%
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        <livewire:estado-producto :producto="$producto"/>
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ \Carbon\Carbon::parse($producto->created_at)->format('d-m-Y / H:i:s') }}
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ \App\Models\User::find($producto->usuario_ultima_modificacion_id)->name }}
                                        {{ \App\Models\User::find($producto->usuario_ultima_modificacion_id)->apellido }}
                                    </td>

                                    <td class="border px-4 py-2 text-center align-middle">
                                        {{ \Carbon\Carbon::parse($producto->updated_at)->format('d-m-Y / H:i:s') }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-800 p-2 text-center font-bold">
                        No hay Productos aún
                    </p>
                @endif
            </div>
        </div>
</x-app-layout>