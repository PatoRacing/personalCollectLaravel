<div>
    @php
    $bgColorClass = $producto->estado === 1 ? 'bg-green-700 hover:bg-green-800' : 'bg-red-600 hover:bg-red-700';
    @endphp
    <button
        class="{{ $bgColorClass }} text-white w-28 h-10 rounded"
        wire:click="estadoProducto({{ $producto->id }})"
    >
        @if ($producto->estado === 1)
                Activo
        @else 
                Inactivo
        @endif
    </button>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('estadoActualizado', function() {
            Swal.fire({
                title: 'Producto Actualizado',
                text: 'El estado del producto se actualizo correctamente',
                icon: 'success'
            }).then(()=> {
                        location.reload();
                    })
            }
        )
    </script>
@endpush
