<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Notas</title>
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #64748b;
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --background: #f8fafc;
            --surface: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: var(--surface);
            padding: 32px;
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #0112fdff, #a1b7ffff);
            /* background-clip: text; */
            /* -webkit-background-clip: text; */
            /* -webkit-text-fill-color: transparent; */
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
            padding: 24px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: var(--radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .control-group label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .control-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border: none;
            border-radius: var(--radius);
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            min-width: 44px;
            min-height: 44px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: var(--danger-hover);
            transform: translateY(-1px);
        }

        .btn-color {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.8);
            box-shadow: var(--shadow);
            position: relative;
        }

        .btn-color::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: rgba(0, 0, 0, 0.6);
            font-weight: bold;
            opacity: 0;
            transition: var(--transition);
        }

        .btn-color.active::after {
            opacity: 1;
        }

        .btn-color:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .search-box {
            position: relative;
            margin-bottom: 24px;
        }

        .search-input {
            width: 100%;
            padding: 16px 48px 16px 16px;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            background: var(--surface);
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .notes-grid {
            display: grid;
            gap: 20px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .grid-1 { grid-template-columns: 1fr; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); }
        .grid-4 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        .grid-5 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }

        .note {
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
            background: var(--surface);
        }

        .note:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .note::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
        }

        .note.bg-lightyellow {
            background: linear-gradient(135deg, #fefce8, #fde047);
            border-color: #fde047;
        }

        .note.bg-lightgreen {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border-color: #4ade80;
        }

        .note.bg-lightblue {
            background: linear-gradient(135deg, #f0f9ff, #dbeafe);
            border-color: #3b82f6;
        }

        .note.bg-lightpink {
            background: linear-gradient(135deg, #fdf2f8, #fce7f3);
            border-color: #f472b6;
        }

        .note.bg-lightpurple {
            background: linear-gradient(135deg, #faf5ff, #f3e8ff);
            border-color: #a855f7;
        }
        
        .note-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .note-content {
            color: var(--text-secondary);
            white-space: pre-wrap;
            word-wrap: break-word;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .note-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: auto;
        }

        .note-actions form {
            display: inline;
        }

        .empty-state {
            text-align: center;
            padding: 64px 24px;
            color: var(--text-secondary);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .controls {
                grid-template-columns: 1fr;
            }

            .control-buttons {
                justify-content: center;
            }

            .notes-grid {
                grid-template-columns: 1fr !important;
            }

            .header h1 {
                font-size: 2rem;
            }
        }

        .fade-enter {
            opacity: 0;
            transform: translateY(20px);
        }

        .fade-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: var(--transition);
        }

        .color-selector {
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }

        .color-selector select {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 8px;
            background: var(--surface);
            color: var(--text-primary);
            cursor: pointer;
            font-size: 0.875rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 30px;
        }

        .color-selector::after {
            content: '▼';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Mis Notas</h1>
            <p>Organiza tus ideas de forma elegante</p>
        </div>

        <div class="controls">
            <div class="control-group">
                <label>🔑 Autenticación</label>
                <div class="control-buttons">
                    @auth
                        <span style="align-self: center; margin-right: 10px;">Hola, {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Cerrar Sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                    @endauth
                </div>
            </div>

            <div class="control-group">
                <label>🚀 Acciones</label>
                <div class="control-buttons">
                    <a href="/notes/create" class="btn btn-primary">
                        ➕ Nueva Nota
                    </a>
                </div>
            </div>

            <div class="control-group">
                <label>📊 Vista</label>
                <div class="control-buttons">
                    <button class="btn btn-secondary" onclick="changeGrid('grid-1')" title="1 columna">1</button>
                    <button class="btn btn-secondary" onclick="changeGrid('grid-2')" title="2 columnas">2</button>
                    <button class="btn btn-secondary active" onclick="changeGrid('grid-3')" title="3 columnas">3</button>
                    <button class="btn btn-secondary" onclick="changeGrid('grid-4')" title="4 columnas">4</button>
                    <button class="btn btn-secondary" onclick="changeGrid('grid-5')" title="5 columnas">5</button>
                </div>
            </div>
        </div>

        <div class="search-box">
            <input type="text" class="search-input" id="searchInput" placeholder="🔍 Buscar notas...">
            <span class="search-icon">⌕</span>
        </div>

        <div class="stats">
            <div class="stat">
                <span>📋</span>
                <span id="total-notes"></span>
            </div>
            <div class="stat">
                <span>👁️</span>
                <span id="visible-notes"></span>
            </div>
        </div>

        <div id="notes-list" class="notes-grid grid-3">
            @forelse ($notes as $note)
                <div class="note {{ $note->color_class ?? 'bg-default' }}">
                    <h3 class="note-title">{{ $note->title }}</h3>
                    <p class="note-content">{{ $note->content }}</p>

                    <div class="note-actions">
                        <a href="/notes/{{ $note->id }}/edit" class="btn btn-primary">
                            ✏️ Editar
                        </a>

                        <form action="/notes/{{ $note->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">🗑️ Eliminar</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3>No se encontraron notas</h3>
                    <p>¡Crea tu primera nota para empezar!</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Funcionalidad de búsqueda
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            const notes = document.querySelectorAll('.note');
            const notesList = document.getElementById('notes-list');
            const visibleNotesCount = document.getElementById('visible-notes');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let visibleCount = 0;

                notes.forEach(note => {
                    const title = note.querySelector('.note-title').textContent.toLowerCase();
                    const content = note.querySelector('.note-content').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || content.includes(searchTerm)) {
                        note.style.display = 'block';
                        visibleCount++;
                    } else {
                        note.style.display = 'none';
                    }
                });

                visibleNotesCount.textContent = `${visibleCount} visible${visibleCount !== 1 ? 's' : ''}`;
            });
        }
        
        // Cargar preferencias de cuadrícula
        function loadPreferences() {
            const savedGrid = localStorage.getItem('preferred-grid');
            
            if (savedGrid) {
                changeGrid(savedGrid);
                document.querySelectorAll('.control-buttons .btn-secondary').forEach(btn => {
                    if (btn.textContent.trim() === savedGrid.replace('grid-', '')) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }
        }
        
        // Actualizar contadores
        function updateStats() {
            const totalNotes = document.querySelectorAll('.note').length;
            document.getElementById('total-notes').textContent = `${totalNotes} nota${totalNotes !== 1 ? 's' : ''}`;
            document.getElementById('visible-notes').textContent = `${totalNotes} visible${totalNotes !== 1 ? 's' : ''}`;
        }
        
        // Funciones de cambio de vista
        function changeGrid(className) {
            const notesList = document.getElementById('notes-list');
            notesList.className = 'notes-grid ' + className;
            localStorage.setItem('preferred-grid', className);
        }

        // Inicializar aplicación
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-secondary').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.btn-secondary').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            loadPreferences();
            updateStats();
            setupSearch();
        });
    </script>
</body>
</html>