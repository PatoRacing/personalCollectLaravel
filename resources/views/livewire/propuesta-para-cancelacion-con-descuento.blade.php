<div>
    <h2 class="text-center rounded  bg-indigo-600 font-bold text-white border-y-2 p-2 m-4">Cuotas con descuento </h2>
    
    @if ($formulario)
        <form class="container p-2 mt-1 border rounded" wire:submit.prevent='calcularQuitaConDescuento'>
            
            <!--Porcentaje de Quita -->
            <div class="">
                <x-input-label for="porcentaje_quita" :value="__('Porcentaje de quita:')" />
                <x-text-input
                    id="porcentaje_quita"
                    placeholder="Ingresar quita ofrecida"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="porcentaje_quita"
                    :value="old('porcentaje_quita')"
                    />
                <x-input-error :messages="$errors->get('porcentaje_quita')" class="mt-2" />
            </div>

            <!--Cantidad de Cuotas -->
            <div class="mt-2">
                <x-input-label for="cantidad_de_cuotas_uno" :value="__('Cantidad de Cuotas:')" />
                <x-text-input
                    id="cantidad_de_cuotas_uno"
                    placeholder="Ingresá la cantidad de cuotas"
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="cantidad_de_cuotas_uno"
                    :value="old('cantidad_de_cuotas_uno')"
                    />
                <x-input-error :messages="$errors->get('cantidad_de_cuotas_uno')" class="mt-2" />
            </div>

            <x-primary-button class="mt-4 pt-3 pb-3 w-full justify-center bg-blue-800 hover:bg-blue-900">
                {{ __('Calcular') }}
            </x-primary-button>
        </form>
    @endif

    @if ($resultados)
        <div class="border rounded p-2">
            <div class="p-2 ">
                <h2 class="font-bold uppercase">Detalle de la Negociacion:</h2>
                <p>Monto negociado: ${{number_format($monto_negociado, 2, ',', '.')}}</p>
                <p>Honorarios: ${{number_format($monto_honorarios, 2, ',', '.') }} ({{$honorarios}}%)</p>
                <p>Monto Total: ${{number_format($monto_total, 2, ',', '.') }}</p>
                <p>Porcentaje de Quita: {{number_format($porcentaje_quita, 2, ',', '.') }}%</p>
                <p>Monto de la Quita: ${{number_format($monto_quita, 2, ',', '.') }}</p>
                <p>Monto a Pagar en cuotas: ${{number_format($monto_a_pagar_en_cuotas, 2, ',', '.') }}</p>
                <p>Cantidad de cuotas: {{$cantidad_de_cuotas_uno}}</p>
                <p>Monto de la cuota: ${{number_format($monto_de_cuotas_uno, 2, ',', '.') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                <button class="bg-green-700 px-4 py-2 text-white rounded hover:bg-green-800"
                        wire:click="confirmarPropuesta" 
                >
                    Confirmar
                </button>
                <button class="bg-red-700 px-4 py-2 text-white rounded hover:bg-red-800"
                        wire:click="cancelarPropuesta"
                >
                    Cancelar
                </button>
            </div>
        </div>
    @endif

    @if($confirmarPropuesta)
        <form class="container p-2 border rounded" wire:submit.prevent='guardarPropuesta'> 
            <h2 class="text-center rounded  bg-white font-bold text-gray-600 border-y-2 p-2 ">Confirmar Propuesta</h2>
            <div class="bg-white grid  px-4 py-2">
                <!-- Accion -->
                <div>
                    <x-input-label for="accion" :value="__('Accion realizada')" />
                    <select
                        id="accion"
                        class="block mt-1 w-full rounded-md border-gray-300"
                        wire:model="accion"
                    >
                        <option selected value=""> - Seleccionar -</option>
                        <option>Llamada Entrante TP (Fijo)</option>
                        <option>Llamada Saliente TP (Fijo)</option>
                        <option>Llamada Entrante TP (Celular)</option>
                        <option>Llamada Saliente TP (Celular)</option>
                        <option>Llamada Entrante WP (Celular)</option>
                        <option>Llamada Saliente WP (Celular)</option>
                        <option>Chat WP (Celular)</option>
                        <option>Mensaje SMS (Celular)</option>
                    </select>
                    <x-input-error :messages="$errors->get('accion')" class="mt-2" />
                </div>

                <!-- Resultado -->
                <div class="mt-2">
                    <x-input-label for="estado" :value="__('Estado')" />
                    <select
                        id="estado"
                        class="block mt-1 w-full rounded-md border-gray-300"
                        wire:model="estado"
                    >
                        <option selected value="">-- Seleccionar --</option>
                        <option>Negociación</option>
                        <option>Propuesta de Pago</option>
                    </select>
                    <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                </div>

                <!-- Vencimiento -->
                <div class="mt-2">
                    <x-input-label for="vencimiento" :value="__('Vencimiento:')" />
                    <x-text-input
                        id="vencimiento"
                        class="block mt-1 w-full"
                        type="date"
                        wire:model="vencimiento"
                        :value="old('vencimiento')"
                        />
                    <x-input-error :messages="$errors->get('vencimiento')" class="mt-2" />
                </div>

                <!-- Observacion -->
                <div class="mt-2">
                    <x-input-label for="observaciones" :value="__('Observaciones')" />
                    <textarea
                        wire:model="observaciones"
                        id="observaciones"
                        class="block mt-1 w-full"
                        type="text"
                        placeholder="Describe brevemente la acción"
                        ></textarea>
                    <div class="mt-2 text-sm text-gray-500">
                        Caracteres restantes: {{ 255 - strlen($observaciones) }}
                    </div>
                    <x-input-error :messages="$errors->get('observaciones')" class="mt-2" />
                </div>

                <!--Operacion Id-->
                <x-text-input
                    id="operacion_id"
                    type="hidden"
                    wire:model="operacion_id"
                    :value="old('operacion->id')"
                />
            </div>

            <x-primary-button class="mt-4 pt-3 pb-3 w-full justify-center bg-blue-800 hover:bg-blue-900">
                {{ __('Guardar') }}
            </x-primary-button>
        </form>
    @endif
</div>
