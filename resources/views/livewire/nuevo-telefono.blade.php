<div>
    <form 
        class="container p-2 mt-3"
        wire:submit.prevent='nuevoTelefono'
        >
                
        @csrf
        <h2 class="text-center bg-white font-bold text-gray-600 border-y-2 p-4 mb-4">Completa todos los campos</h2>
        <div class="bg-white grid grid-cols-1 gap-4 md:grid-cols-2 px-4 py-4">
            
            <!--Tipo -->
            <div class="mt-2">
                <x-input-label for="tipo" :value="__('Tipo de teléfono:')" />
                <x-text-input
                    id="tipo"
                    placeholder="Ej: Celular, WhatsApp, Línea, Personal"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="tipo"
                    :value="old('tipo')"
                    />
                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>

            <!-- Contacto -->
            <div class="mt-2">
                <x-input-label for="contacto" :value="__('Contacto:')" />
                <x-text-input
                    id="contacto"
                    placeholder="Ej: Titular, Referencia, Laboral, Familiar"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="contacto"
                    :value="old('contacto')"
                    />
                <x-input-error :messages="$errors->get('contacto')" class="mt-2" />
            </div>

            <!-- Número -->
            <div class="mt-2">
                <x-input-label for="numero" :value="__('Número:')" />
                <x-text-input
                    id="numero"
                    placeholder="Indicar número y prefijo"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="numero"
                    :value="old('numero')"
                    />
                <x-input-error :messages="$errors->get('numero')" class="mt-2" />
            </div>

            <!-- Estado -->
            <div class="mt-2">
                <x-input-label for="estado" :value="__('Estado:')" />
                <select
                    id="estado"
                    class="block mt-1 w-full rounded-md border-gray-300"
                    wire:model="estado"
                >
                    <option selected value="">-- Seleccionar --</option>
                    <option value="1">Verificado</option>
                    <option value="2">No Verificado</option>
                </select>
                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
            </div>

        </div>
        <x-primary-button class="mt-4 pt-3 pb-3 w-full justify-center bg-blue-800 hover:bg-blue-900">
            {{ __('Nueva gestión') }}
        </x-primary-button>
    
    </form>
</div>
