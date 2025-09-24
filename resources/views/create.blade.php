<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Nota</title>
</head>
<body>
    <h1>Crear nueva nota</h1>
    
    <form action="/notes" method="POST">
        @csrf
        
        <label for="title">Título:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="content">Contenido:</label><br>
        <textarea id="content" name="content" rows="10" required></textarea><br><br>
        
        <button type="submit">Guardar Nota</button>
    </form>
</body>
</html>