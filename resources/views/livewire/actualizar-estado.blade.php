<div>
    @php
    $bgColorClass = $usuario->estado_de_usuario_id === 1 ? 'bg-green-700 hover:bg-green-800' : 'bg-red-600 hover:bg-red-700';
    @endphp
    <button
        class="{{ $bgColorClass }} text-white w-28 h-10 rounded"
        wire:click="actualizarEstado({{ $usuario->id }})"

    >
    @if ($usuario->estado_de_usuario_id === 1)
            Activo
    @else 
            Inactivo
    @endif
    </button>
    
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('actualizacionCompleta', function() {
            Swal.fire({
                title: 'Usuario Actualizado',
                text: 'El estado del usuario se actualizo correctamente',
                icon: 'success'
            }).then(()=> {
                        location.reload();
                    })
            }
        )
    </script>
@endpush