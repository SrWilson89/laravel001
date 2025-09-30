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

                <form method="POST" action="{{ route('notes.update', $note) }}">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo Título --}}
                    <div class="mb-4">
                        <label for="title" class="block font-medium text-sm text-gray-700">Título</label>
                        <input id="title" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" type="text" name="title" value="{{ old('title', $note->title) }}" required autofocus />
                    </div>

                    {{-- Campo Contenido --}}
                    <div class="mb-4">
                        <label for="content" class="block font-medium text-sm text-gray-700">Contenido</label>
                        <textarea id="content" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" name="content" rows="6" required>{{ old('content', $note->content) }}</textarea>
                    </div>

                    {{-- Selector de Color --}}
                    <div class="mb-6">
                        <label for="color_class" class="block font-medium text-sm text-gray-700">Color de la Nota</label>
                        <select id="color_class" name="color_class" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="bg-white" class="bg-white" {{ old('color_class', $note->color_class) == 'bg-white' ? 'selected' : '' }}>Blanco (Por Defecto)</option>
                            <option value="bg-red-100" class="bg-red-100" {{ old('color_class', $note->color_class) == 'bg-red-100' ? 'selected' : '' }}>Rojo Claro</option>
                            <option value="bg-orange-100" class="bg-orange-100" {{ old('color_class', $note->color_class) == 'bg-orange-100' ? 'selected' : '' }}>Naranja Rojizo</option>
                            <option value="bg-amber-100" class="bg-amber-100" {{ old('color_class', $note->color_class) == 'bg-amber-100' ? 'selected' : '' }}>Naranja</option>
                            <option value="bg-yellow-100" class="bg-yellow-100" {{ old('color_class', $note->color_class) == 'bg-yellow-100' ? 'selected' : '' }}>Amarillo Naranja</option>
                            <option value="bg-lime-100" class="bg-lime-100" {{ old('color_class', $note->color_class) == 'bg-lime-100' ? 'selected' : '' }}>Amarillo</option>
                            <option value="bg-green-100" class="bg-green-100" {{ old('color_class', $note->color_class) == 'bg-green-100' ? 'selected' : '' }}>Verde Amarillo</option>
                            <option value="bg-emerald-100" class="bg-emerald-100" {{ old('color_class', $note->color_class) == 'bg-emerald-100' ? 'selected' : '' }}>Verde</option>
                            <option value="bg-cyan-100" class="bg-cyan-100" {{ old('color_class', $note->color_class) == 'bg-cyan-100' ? 'selected' : '' }}>Azul Verdoso</option>
                            <option value="bg-blue-100" class="bg-blue-100" {{ old('color_class', $note->color_class) == 'bg-blue-100' ? 'selected' : '' }}>Azul</option>
                            <option value="bg-indigo-100" class="bg-indigo-100" {{ old('color_class', $note->color_class) == 'bg-indigo-100' ? 'selected' : '' }}>Azul Violeta</option>
                            <option value="bg-purple-100" class="bg-purple-100" {{ old('color_class', $note->color_class) == 'bg-purple-100' ? 'selected' : '' }}>Violeta</option>
                            <option value="bg-pink-100" class="bg-pink-100" {{ old('color_class', $note->color_class) == 'bg-pink-100' ? 'selected' : '' }}>Rojo Violeta</option>
                        </select>
                    </div>
                    
                    {{-- Checkbox Público/Privado --}}
                    <div class="mb-6 flex items-center">
                        <input id="is_public" name="is_public" type="checkbox" value="1" {{ old('is_public', $note->is_public) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="is_public" class="ml-2 block text-sm font-medium text-gray-700">
                            Hacer pública (Visible para otros usuarios)
                        </label>
                    </div>
                    
                    {{-- Botón Guardar --}}
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Actualizar Nota
                        </button>
                    </div>
                </form>

                <hr class="my-8 border-gray-200">

                {{-- Previsualización de la Nota --}}
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vista Previa</h3>
                <div id="note-preview" class="p-5 rounded-lg shadow-md border border-gray-200" style="min-height: 150px;">
                    <h4 class="note-title text-xl font-bold mb-2">{{ $note->title }}</h4>
                    <p class="note-content text-gray-700 text-sm">{{ $note->content }}</p>
                </div>

            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const titleInput = document.getElementById('title');
            const contentTextarea = document.getElementById('content');
            const colorSelect = document.getElementById('color_class');
            const notePreview = document.getElementById('note-preview');

            // NUEVAS CLASES DE COLOR DE TAILWIND ESTÁNDAR
            const colorClasses = [
                'bg-white', 'bg-red-100', 'bg-orange-100', 'bg-amber-100', 'bg-yellow-100', 
                'bg-lime-100', 'bg-green-100', 'bg-emerald-100', 'bg-cyan-100', 'bg-blue-100', 
                'bg-indigo-100', 'bg-purple-100', 'bg-pink-100'
            ];

            function updatePreview() {
                const selectedColorClass = colorSelect.value;
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