@section('titulo')
    Importar Cartera de Cliente
@endsection

<x-app-layout>
    <div class="container mx-auto ">
        <div class="overflow-x-auto">
            <div class="p-4 sticky left-0">
                <h1 class="font-extrabold text-2xl bg-white p-4 text-gray-900 text-center mb-5">{{$cliente->nombre}}: Importar Cartera</h1>
                <a href="{{route('clientes')}}" class="text-white bg-blue-800 hover:bg-blue-900 px-5 py-3 rounded">Volver</a>

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-600 text-red-800 font-bold mt-3 p-3">
                        {!! nl2br($errors->first('importar')) !!}
                    </div>
                @elseif(session('message'))
                    <div class="bg-green-100 border-l-4 border-green-600 text-green-800 font-bold mt-3 p-3">
                        {!! nl2br(session('message')) !!}
                    </div>
                @endif
               
            </div>
            <form 
                class="container p-2"
                method="POST"
                action="{{route('almacenar.cartera.cliente', ['cliente' => $cliente->id])}}"
                enctype="multipart/form-data"
                >
                @csrf
                
                <h2 class="text-center bg-white font-bold text-gray-600 border-y-2 p-4 mb-4">Selecciona el archivo excel a importar</h2>
                <div class="bg-white grid grid-cols-1 gap-4 md:grid-cols-3 px-4 py-4">
                    <!-- CLiente -->
                    <div class="mt-2">
                        <x-input-label for="nombre" :value="__('Nombre del Cliente')" />
                        <x-text-input
                            id="nombre"
                            placeholder="Nombre del Cliente"
                            class="block mt-1 w-full  bg-gray-200"
                            type="text"
                            name="nombre"
                            :value="$cliente->nombre"
                            disabled
                            />
                    </div>

                    <!--Cliente Id-->
                    <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                    <!-- Usuario creador -->
                    <div class="mt-2">
                        <x-input-label for="usuario_ultima_modificacion_id" :value="__('Usuario')" />
                        <x-text-input
                            id="usuario_ultima_modificacion_id"
                            class="block mt-1 w-full bg-gray-200"
                            type="text"
                            value="{{ $nombreUsuario }}"
                            name="usuario_ultima_modificacion_id"
                            readonly
                            />
                    </div>
                    
                    <!-- Archivo -->
                    <div class="mt-2">
                        <x-input-label for="importar" :value="__('Importar')" />
                        <x-text-input
                            id="importar"
                            placeholder="Seleccionar archivo"
                            class="block mt-1 w-full border p-1.5"
                            type="file"
                            name="importar"
                            accept=".xls, .xlsx"
                            />
                        <x-input-error :messages="$errors->get('importar')" class="mt-2" />
                    </div> 
                </div>
                <x-primary-button class="mt-4 pt-3 pb-3 w-full justify-center bg-blue-800 hover:bg-blue-900">
                    {{ __('Importar') }}
                </x-primary-button>
            
            </form>
        </div>
    </div>
</x-app-layout>