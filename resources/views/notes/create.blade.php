@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="button-container">
            {{-- Icono Volver --}}
            <a href="/" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h1>Crear nueva nota</h1>
            <span></span>
        </div>
        
        <form action="/notes" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="content">Contenido:</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="color_class">Color:</label>
                <select id="color_class" name="color_class">
                    <option value="bg-default">Predeterminado</option>
                    <option value="bg-red">Rojo</option>
                    <option value="bg-orange-red">Rojo-Naranja</option>
                    <option value="bg-orange">Naranja</option>
                    <option value="bg-yellow-orange">Amarillo-Naranja</option>
                    <option value="bg-yellow">Amarillo</option>
                    <option value="bg-yellow-green">Amarillo-Verde</option>
                    <option value="bg-green">Verde</option>
                    <option value="bg-green-cyan">Verde-Cian</option>
                    <option value="bg-cyan">Cian</option>
                    <option value="bg-cyan-blue">Cian-Azul</option>
                    <option value="bg-blue">Azul</option>
                    <option value="bg-blue-magenta">Azul-Magenta</option>
                    <option value="bg-magenta">Magenta</option>
                    <option value="bg-magenta-red">Magenta-Rojo</option>
                </select>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1">
                <label class="form-check-label" for="is_public">
                    Hacer esta nota pública
                </label>
            </div>
            
            <div class="note-preview-container">
                <h3>Vista Previa</h3>
                <div id="note-preview" class="note-preview bg-default">
                    <h3 class="note-title">Título de la nota</h3>
                    <div class="note-content">Contenido de la nota</div>
                </div>
            </div>

            {{-- Icono Crear Nota --}}
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Crear Nota
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