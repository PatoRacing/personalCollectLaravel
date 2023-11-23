<div>
    <form 
        class="container p-2"
        wire:submit.prevent='crearProducto'
        >
                
        @csrf
        <h2 class="text-center bg-white font-bold text-gray-600 border-y-2 p-4 mb-4">Completa todos los campos</h2>
        <div class="bg-white grid grid-cols-1 gap-4 md:grid-cols-2 px-4 py-4">
            <!-- Nombre -->
            <div class="mt-2">
                <x-input-label for="nombre" :value="__('Nombre del Producto')" />
                <x-text-input
                    id="nombre"
                    placeholder="Nombre del Producto"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="nombre"
                    :value="old('nombre')"
                    />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            <!-- Honorarios -->
            <div class="mt-2">
                <x-input-label for="honorarios" :value="__('Honorarios')" />
                <x-text-input
                    id="honorarios"
                    placeholder="Indicá el valor de los honorarios"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="honorarios"
                    :value="old('honorarios')"
                    />
                <x-input-error :messages="$errors->get('honorarios')" class="mt-2" />
            </div>

            <!-- Comision Cliente -->
            <div class="mt-2">
                <x-input-label for="comision_cliente" :value="__('Comisión del Cliente')" />
                <x-text-input
                    id="comision_cliente"
                    placeholder="Indicá la comisión del cliente"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="comision_cliente"
                    :value="old('comision_cliente')"
                    />
                <x-input-error :messages="$errors->get('comision_cliente')" class="mt-2" />
            </div>

            <!-- Cliente -->
            <div class="mt-2">
                <x-input-label for="cliente_id" :value="__('Selecciona un cliente')" />
                <select
                    id="cliente_id"
                    class="block mt-1 w-full rounded-md border-gray-300"
                    wire:model="cliente_id"
                >
                    <option value="">Selecciona un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
            </div>
            
        </div>
        <x-primary-button class="mt-4 pt-3 pb-3 w-full justify-center bg-blue-800 hover:bg-blue-900">
            {{ __('Crear producto') }}
        </x-primary-button>
    
    </form>
</div>
