<x-app-layout>
    {{-- 1. Cabecera (Título de la página) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📝 Editar Nota: {{ $note->title }}
        </h2>
    </x-slot>

    {{-- 2. Contenido Principal --}}
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                
                {{-- Botón Volver --}}
                <div class="mb-6">
                    <a href="{{ route('notes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                        ← Volver a Notas
                    </a>
                </div>

                {{-- Muestra los errores de validación --}}
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">¡Hay problemas con el formulario!</p>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Contenedor Flex para Formulario y Preview --}}
                <div class="flex flex-col md:flex-row gap-8">
                    
                    {{-- Formulario de Edición --}}
                    <form action="{{ route('notes.update', $note) }}" method="POST" class="w-full md:w-2/3">
                        @csrf
                        @method('PUT')
                        
                        {{-- Campo Título --}}
                        <div class="mb-4">
                            <label for="title" class="block font-medium text-sm text-gray-700">Título</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('title', $note->title) }}" required autofocus>
                        </div>
                        
                        {{-- Campo Contenido --}}
                        <div class="mb-4">
                            <label for="content" class="block font-medium text-sm text-gray-700">Contenido</label>
                            <textarea name="content" id="content" rows="8" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('content', $note->content) }}</textarea>
                        </div>
                        
                        {{-- Checkbox Favorita y Pública (CORREGIDO) --}}
                        <div class="flex items-center space-x-6 mb-4">
                            <label for="is_favorite" class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_favorite" id="is_favorite" value="1" class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500" 
                                    {{-- Lógica de Blade para precargar el estado --}}
                                    {{ old('is_favorite', $note->is_favorite) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">⭐ Marcar como Favorita</span>
                            </label>

                            <label for="is_public" class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_public" id="is_public" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                    {{-- Lógica de Blade para precargar el estado --}}
                                    {{ old('is_public', $note->is_public) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">🌐 Hacer Pública</span>
                            </label>
                        </div>

                        {{-- Selector de Color (CORREGIDO) --}}
                        <div class="mb-4">
                            <label for="color" class="block font-medium text-sm text-gray-700">Color de Fondo</label>
                            <select name="color" id="color" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($colors as $colorClass)
                                    @php
                                        // Mapeo simple de nombres a clases de Tailwind (usando bg-tailwind-200 para la apariencia visual en el select)
                                        $displayClass = str_replace('bg-', 'bg-', $colorClass); 
                                        $displayName = match($colorClass) {
                                            'bg-default' => 'Blanco (Por Defecto)',
                                            'bg-red' => 'Rojo Suave',
                                            'bg-orange-red' => 'Rojo Naranja Suave',
                                            'bg-orange' => 'Naranja Suave',
                                            'bg-yellow-orange' => 'Naranja Amarillo Suave',
                                            'bg-yellow' => 'Amarillo Suave',
                                            'bg-yellow-green' => 'Verde Amarillo Suave',
                                            'bg-green' => 'Verde Suave',
                                            'bg-blue-green' => 'Verde Azul Suave',
                                            'bg-blue' => 'Azul Suave',
                                            'bg-blue-violet' => 'Azul Violeta Suave',
                                            'bg-violet' => 'Violeta Suave',
                                            'bg-red-violet' => 'Violeta Rojo Suave',
                                            default => 'Color Personalizado'
                                        };
                                    @endphp
                                    <option value="{{ $colorClass }}" 
                                            class="{{ $displayClass }}" 
                                            {{ old('color', $note->color) == $colorClass ? 'selected' : '' }}>
                                        {{ $displayName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Selector de Etiquetas (Tags) --}}
                        <div class="mb-6">
                            <label for="tag_ids" class="block font-medium text-sm text-gray-700 mb-1">Etiquetas (Tags)</label>
                            <div class="flex flex-wrap gap-3 p-2 border border-gray-300 rounded-md bg-gray-50">
                                @forelse ($tags as $tag)
                                    <label class="flex items-center cursor-pointer transition duration-150 ease-in-out hover:bg-indigo-50 px-3 py-1 rounded-full">
                                        {{-- Usamos $selected_tags para verificar si está marcada --}}
                                        <input type="checkbox" 
                                               name="tag_ids[]" 
                                               value="{{ $tag->id }}" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                               {{-- Lógica de Blade para precargar etiquetas seleccionadas --}}
                                               {{ in_array($tag->id, old('tag_ids', $selected_tags)) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">{{ $tag->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No hay etiquetas disponibles.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Botón Guardar --}}
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            Actualizar Nota
                        </button>
                    </form>

                    {{-- Previsualización de la Nota (Aplica el color guardado) --}}
                    <div class="w-full md:w-1/3 flex justify-center pt-10">
                         <div id="note-preview" class="w-full max-w-xs p-6 border border-gray-300 rounded-xl shadow-lg transition duration-300 {{ $note->color ?? 'bg-default' }} min-h-64">
                            <p class="text-sm text-gray-500 mb-2">Previsualización:</p>
                            <h3 class="note-title text-xl font-bold mb-2 break-words text-gray-800">{{ $note->title }}</h3>
                            <p class="note-content text-gray-600 line-clamp-6 break-words">{{ $note->content }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    {{-- Script de Previsualización (Asegura la compatibilidad de colores) --}}
    <style>
        .bg-default { background-color: #ffffff; }
        .bg-red { background-color: #fecaca; } /* Rojo Suave */
        .bg-orange-red { background-color: #fed7aa; } /* Rojo Naranja Suave */
        .bg-orange { background-color: #fde68a; } /* Naranja Suave */
        .bg-yellow-orange { background-color: #d9f99d; } /* Naranja Amarillo Suave */
        .bg-yellow { background-color: #a7f3d0; } /* Amarillo Suave */
        .bg-yellow-green { background-color: #a5f3fc; } /* Verde Amarillo Suave */
        .bg-green { background-color: #93c5fd; } /* Verde Suave */
        .bg-blue-green { background-color: #a5b4fc; } /* Verde Azul Suave */
        .bg-blue { background-color: #c4b5fd; } /* Azul Suave */
        .bg-blue-violet { background-color: #e879f9; } /* Azul Violeta Suave */
        .bg-violet { background-color: #fbcfe8; } /* Violeta Suave */
        .bg-red-violet { background-color: #fda4af; } /* Violeta Rojo Suave */
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const contentTextarea = document.getElementById('content');
            const colorSelect = document.getElementById('color');
            const notePreview = document.getElementById('note-preview');

            // Lista de todas las clases de color
            const colorClasses = [
                'bg-default', 'bg-red', 'bg-orange-red', 'bg-orange', 'bg-yellow-orange', 
                'bg-yellow', 'bg-yellow-green', 'bg-green', 'bg-blue-green', 'bg-blue', 
                'bg-blue-violet', 'bg-violet', 'bg-red-violet'
            ];

            function updatePreview() {
                const selectedColorClass = colorSelect.value;
                const newTitle = titleInput.value || 'Título de la nota';
                const newContent = contentTextarea.value || 'Contenido de la nota';

                // 1. Limpia todas las clases de color anteriores
                colorClasses.forEach(cls => notePreview.classList.remove(cls));
                
                // 2. Aplica la nueva clase de color
                // Se usa la clase 'bg-default' si no hay color o si se selecciona el valor 'bg-default'
                notePreview.classList.add(selectedColorClass || 'bg-default');
                
                // 3. Actualiza el texto
                notePreview.querySelector('.note-title').textContent = newTitle;
                notePreview.querySelector('.note-content').textContent = newContent;
            }

            titleInput.addEventListener('input', updatePreview);
            contentTextarea.addEventListener('input', updatePreview);
            colorSelect.addEventListener('change', updatePreview);
            
            // Llama a la función al cargar la página para la vista inicial
            updatePreview();
        });
    </script>
</x-app-layout>
