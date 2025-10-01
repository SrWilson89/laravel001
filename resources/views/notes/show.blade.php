<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de la Nota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Tarjeta de la Nota --}}
            <div class="{{ $note->color ?? 'bg-default' }} shadow-xl sm:rounded-lg p-6 lg:p-8 border-l-4 border-{{ $note->color ? str_replace('bg-', '', $note->color) . '-600' : 'indigo-600' }}">

                {{-- Encabezado con título y botones --}}
                <div class="flex justify-between items-start mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 break-words pr-4">
                        {{ $note->title }}
                    </h1>
                    
                    {{-- Opciones de acción --}}
                    <div class="flex space-x-2 shrink-0">
                        @can('update', $note)
                            {{-- Botón Editar --}}
                            <a href="{{ route('notes.edit', $note) }}" 
                               class="text-gray-500 hover:text-indigo-600 transition duration-150" 
                               title="Editar Nota">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487z" />
                                </svg>
                            </a>
                        @endcan
                        
                        @can('delete', $note)
                            {{-- Botón Eliminar (Formulario) --}}
                            <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta nota? Esta acción es irreversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-600 transition duration-150" title="Eliminar Nota">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.93a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v.916c0 3.045-3.085 5.253-5.59 5.253-2.505 0-5.59-2.208-5.59-5.253V5.79m7.5 0c0-1.242.414-2.25 1.116-2.25h1.768c.702 0 1.116 1.008 1.116 2.25z" />
                                    </svg>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                {{-- Contenido de la Nota --}}
                <div class="text-gray-700 whitespace-pre-wrap leading-relaxed text-lg mb-6">
                    {!! nl2br(e($note->content)) !!}
                </div>

                {{-- Metadatos y Tags --}}
                <div class="border-t pt-4 border-gray-200">
                    <p class="text-sm text-gray-500 mb-3">
                        **Autor:** {{ $note->user->name }} | 
                        **Creada:** {{ $note->created_at->format('M d, Y H:i') }} | 
                        @if($note->is_public)
                            <span class="text-green-600 font-semibold">🌐 Pública</span>
                        @else
                            <span class="text-red-500 font-semibold">🔒 Privada</span>
                        @endif
                    </p>

                    @if ($note->tags->count())
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($note->tags as $tag)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Botón para volver al índice --}}
            <div class="mt-6 text-center">
                <a href="{{ route('notes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Volver a Mis Notas
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
