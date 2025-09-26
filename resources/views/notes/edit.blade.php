@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="button-container">
            {{-- Icono Volver --}}
            <a href="/" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h1>Editar nota</h1>
            <span></span>
        </div>
        
        <form action="/notes/{{ $note->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" value="{{ $note->title }}" required>
            </div>

            <div class="form-group">
                <label for="content">Contenido:</label>
                <textarea id="content" name="content" rows="10" required>{{ $note->content }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="color_class">Color:</label>
                <select id="color_class" name="color_class">
                    <option value="bg-default" {{ ($note->color_class ?? '') == 'bg-default' ? 'selected' : '' }}>Predeterminado</option>
                    <option value="bg-red" {{ ($note->color_class ?? '') == 'bg-red' ? 'selected' : '' }}>Rojo</option>
                    <option value="bg-orange-red" {{ ($note->color_class ?? '') == 'bg-orange-red' ? 'selected' : '' }}>Rojo-Naranja</option>
                    <option value="bg-orange" {{ ($note->color_class ?? '') == 'bg-orange' ? 'selected' : '' }}>Naranja</option>
                    <option value="bg-yellow-orange" {{ ($note->color_class ?? '') == 'bg-yellow-orange' ? 'selected' : '' }}>Amarillo-Naranja</option>
                    <option value="bg-yellow" {{ ($note->color_class ?? '') == 'bg-yellow' ? 'selected' : '' }}>Amarillo</option>
                    <option value="bg-yellow-green" {{ ($note->color_class ?? '') == 'bg-yellow-green' ? 'selected' : '' }}>Amarillo-Verde</option>
                    <option value="bg-green" {{ ($note->color_class ?? '') == 'bg-green' ? 'selected' : '' }}>Verde</option>
                    <option value="bg-green-cyan" {{ ($note->color_class ?? '') == 'bg-green-cyan' ? 'selected' : '' }}>Verde-Cian</option>
                    <option value="bg-cyan" {{ ($note->color_class ?? '') == 'bg-cyan' ? 'selected' : '' }}>Cian</option>
                    <option value="bg-cyan-blue" {{ ($note->color_class ?? '') == 'bg-cyan-blue' ? 'selected' : '' }}>Cian-Azul</option>
                    <option value="bg-blue" {{ ($note->color_class ?? '') == 'bg-blue' ? 'selected' : '' }}>Azul</option>
                    <option value="bg-blue-magenta" {{ ($note->color_class ?? '') == 'bg-blue-magenta' ? 'selected' : '' }}>Azul-Magenta</option>
                    <option value="bg-magenta" {{ ($note->color_class ?? '') == 'bg-magenta' ? 'selected' : '' }}>Magenta</option>
                    <option value="bg-magenta-red" {{ ($note->color_class ?? '') == 'bg-magenta-red' ? 'selected' : '' }}>Magenta-Rojo</option>
                </select>
            </div>
                    
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1" {{ $note->is_public ? 'checked' : '' }}>
                <label class="form-check-label" for="is_public">
                    Hacer esta nota pública
                </label>
            </div>

            <div class="note-preview-container">
                <h3>Vista Previa</h3>
                <div id="note-preview" class="note-preview {{ $note->color_class ?? 'bg-default' }}">
                    <h3 class="note-title">{{ $note->title }}</h3>
                    <div class="note-content">{{ $note->content }}</div>
                </div>
            </div>

            {{-- Icono Guardar Cambios --}}
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualizar Nota
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const contentTextarea = document.getElementById('content');
            const colorSelect = document.getElementById('color_class');
            const notePreview = document.getElementById('note-preview');

            function updatePreview() {
                const selectedColorClass = colorSelect.value;
                const newTitle = titleInput.value || 'Título de la nota';
                const newContent = contentTextarea.value || 'Contenido de la nota';

                notePreview.className = 'note-preview ' + selectedColorClass;
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
@endsection