<td class="font-bold border text-blue-800 hover:text-blue-900 px-4 py-2 text-center sticky left-0
    {{ $index % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
    <button
        wire:click="usuarioNombre({{ $usuario }})"
    >
    {{ $usuario->name }} {{ $usuario->apellido }}
    </button>
</td>


