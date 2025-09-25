<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nota</title>
</head>
<body>
    <div class="container">
        <div class="button-container">
            <a href="/" class="btn btn-secondary">
                Volver
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
                    <option value="bg-lightyellow">Amarillo</option>
                    <option value="bg-lightgreen">Verde</option>
                    <option value="bg-lightblue">Azul</option>
                    <option value="bg-lightpink">Rosa</option>
                    <option value="bg-lightpurple">Morado</option>
                </select>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1">
                <label class="form-check-label" for="is_public">
                    Hacer esta nota pública
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                Crear Nota
            </button>
        </form>
    </div>
</body>
</html>