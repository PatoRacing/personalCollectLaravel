<div class="border p-2 my-2 bg-white rounded">
    <!--Paso 1-->
    <form class="container p-2 mt-1" wire:submit.prevent='pasoUno'>
        <h2 class="text-center rounded  font-bold text-gray-600 border-y-2 p-2 mb-4">ingresa el monto negociado</h2>
        
        <!--Monto negociado -->
        <div class="">
            <x-input-label for="monto_negociado" :value="__('Monto Negociado:')" />
            <x-text-input
                id="monto_negociado"
                placeholder="Ingresar Monto negociado ofrecido"
                class="block mt-1 w-full"
                type="text"
                wire:model="monto_negociado"
                :value="old('monto_negociado')"
                />
            <x-input-error :messages="$errors->get('monto_negociado')" class="mt-2" />
        </div>    

        <x-primary-button class="mt-4 pt-3 pb-3 w-full justify-center bg-blue-800 hover:bg-blue-900">
            {{ __('Siguiente') }}
        </x-primary-button>
    </form>
    @if ($componentes)
        <h2 class="text-center rounded  bg-white font-bold text-gray-600 border-y-2 p-2">Tipos de Propuestas</h2>
        <div class="bg-white grid grid-cols-1 gap-4 md:grid-cols-4 px-4 py-2 ">
            <livewire:propuesta-para-cancelacion 
                :operacion="$operacion"
                :monto_negociado="$monto_negociado"
                :honorarios="$honorarios"
                :deudorId="$deudorId"
            />
            <livewire:propuesta-para-cancelacion-con-descuento
                :operacion="$operacion"
                :monto_negociado="$monto_negociado"
                :honorarios="$honorarios"
                :deudorId="$deudorId"
            />
            <livewire:propuesta-para-cuotas
                :operacion="$operacion"
                :monto_negociado="$monto_negociado"
                :honorarios="$honorarios"
                :deudorId="$deudorId"
            />
            <livewire:propuesta-para-cuotas-variables
                :operacion="$operacion"
                :monto_negociado="$monto_negociado"
                :honorarios="$honorarios"
                :deudorId="$deudorId"
            />
        </div>
    @endif
</div> 