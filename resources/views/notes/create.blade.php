<x-app-layout>
    {{-- 1. Cabecera (Título de la página) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Crear Nueva Nota
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
                
                <form action="{{ route('notes.store') }}" method="POST">
                    @csrf
                    
                    {{-- Título --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Título:</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    {{-- Contenido --}}
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700">Contenido:</label>
                        <textarea id="content" name="content" rows="10" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('content') }}</textarea>
                    </div>
                    
                    {{-- Selector de Color --}}
                    <div class="mb-6">
                        <label for="color_class" class="block text-sm font-medium text-gray-700">Color:</label>
                        <select id="color_class" name="color_class"
                                class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            {{-- Se usa old() para mantener el valor seleccionado tras un error de validación --}}
                            <option value="bg-default" {{ old('color_class') == 'bg-default' ? 'selected' : '' }}>Predeterminado</option>
                            <option value="bg-red" {{ old('color_class') == 'bg-red' ? 'selected' : '' }}>Rojo</option>
                            <option value="bg-orange-red" {{ old('color_class') == 'bg-orange-red' ? 'selected' : '' }}>Rojo-Naranja</option>
                            <option value="bg-orange" {{ old('color_class') == 'bg-orange' ? 'selected' : '' }}>Naranja</option>
                            <option value="bg-yellow-orange" {{ old('color_class') == 'bg-yellow-orange' ? 'selected' : '' }}>Amarillo-Naranja</option>
                            <option value="bg-yellow" {{ old('color_class') == 'bg-yellow' ? 'selected' : '' }}>Amarillo</option>
                            <option value="bg-yellow-green" {{ old('color_class') == 'bg-yellow-green' ? 'selected' : '' }}>Amarillo-Verde</option>
                            <option value="bg-green" {{ old('color_class') == 'bg-green' ? 'selected' : '' }}>Verde</option>
                            <option value="bg-blue-green" {{ old('color_class') == 'bg-blue-green' ? 'selected' : '' }}>Verde-Azul</option>
                            <option value="bg-blue" {{ old('color_class') == 'bg-blue' ? 'selected' : '' }}>Azul</option>
                            <option value="bg-blue-violet" {{ old('color_class') == 'bg-blue-violet' ? 'selected' : '' }}>Azul-Violeta</option>
                            <option value="bg-violet" {{ old('color_class') == 'bg-violet' ? 'selected' : '' }}>Violeta</option>
                            <option value="bg-red-violet" {{ old('color_class') == 'bg-red-violet' ? 'selected' : '' }}>Violeta-Rojo</option>
                        </select>
                    </div>

                    {{-- Checkbox Público --}}
                    <div class="mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_public" id="is_public" value="1" 
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   {{ old('is_public') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Hacer esta nota pública</span>
                        </label>
                    </div>

                    {{-- Previsualización de la Nota (Estilizado con Tailwind) --}}
                    <div class="note-preview-container mb-6 p-4 border border-gray-200 rounded-lg">
                        <h4 class="text-sm font-semibold mb-2 text-gray-600">Previsualización:</h4>
                        <div class="note-preview bg-default p-5 rounded-lg shadow-md border-2 border-gray-100" id="note-preview">
                            <h3 class="note-title text-xl font-bold mb-2">Título de la nota</h3>
                            <div class="note-content text-gray-700 text-sm">Contenido de la nota</div>
                        </div>
                    </div>

                    {{-- Botón de Crear --}}
                    <div class="flex items-center justify-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            Crear Nota
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const contentTextarea = document.getElementById('content');
            const colorSelect = document.getElementById('color_class');
            const notePreview = document.getElementById('note-preview');
            
            // Todas las clases de color posibles para limpiar
            const colorClasses = [
                'bg-default', 'bg-red', 'bg-orange-red', 'bg-orange', 'bg-yellow-orange', 
                'bg-yellow', 'bg-yellow-green', 'bg-green', 'bg-blue-green', 'bg-blue', 
                'bg-blue-violet', 'bg-violet', 'bg-red-violet'
            ];

            function updatePreview() {
                const selectedColorClass = colorSelect.value;
                // Si está vacío, muestra el texto por defecto
                const newTitle = titleInput.value || 'Título de la nota';
                const newContent = contentTextarea.value || 'Contenido de la nota';

                // 1. Limpia todas las clases de color anteriores
                colorClasses.forEach(cls => notePreview.classList.remove(cls));
                
                // 2. Aplica la nueva clase de color
                notePreview.classList.add(selectedColorClass);
                
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