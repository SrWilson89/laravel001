<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Nota</title>
</head>
<body>
    <div class="container">
        <div class="button-container">
            <a href="/" class="btn btn-secondary">
                Volver
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
                    <option value="bg-lightyellow" {{ ($note->color_class ?? '') == 'bg-lightyellow' ? 'selected' : '' }}>Amarillo</option>
                    <option value="bg-lightgreen" {{ ($note->color_class ?? '') == 'bg-lightgreen' ? 'selected' : '' }}>Verde</option>
                    <option value="bg-lightblue" {{ ($note->color_class ?? '') == 'bg-lightblue' ? 'selected' : '' }}>Azul</option>
                    <option value="bg-lightpink" {{ ($note->color_class ?? '') == 'bg-lightpink' ? 'selected' : '' }}>Rosa</option>
                    <option value="bg-lightpurple" {{ ($note->color_class ?? '') == 'bg-lightpurple' ? 'selected' : '' }}>Morado</option>
                </select>
            </div>
                    
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1">
                <label class="form-check-label" for="is_public">
                    Hacer esta nota pública
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                Guardar Cambios
            </button>
        </form>
    </div>
</body>
</html>