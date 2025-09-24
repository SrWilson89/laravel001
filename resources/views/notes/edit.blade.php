<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Nota</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #4a4a4a;
            text-align: center;
        }
        
        /* Estilos del formulario */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        /* Estilos de botones */
        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            color: white;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

    </style>
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

            <button type="submit" class="btn btn-primary">
                Guardar Cambios
            </button>
        </form>
    </div>
</body>
</html>