@extends('layouts.app')

@section('content')
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
                    <a href="{{ route('profile.edit') }}" class="btn btn-success">
                        👤 Perfil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            🚪 Cerrar Sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Registro</a>
                @endauth
            </div>
        </div>

        <div class="control-group">
            <label>🚀 Acciones</label>
            <div class="control-buttons">
                <a href="{{ route('notes.create') }}" class="btn btn-primary">
                    ➕ Nueva Nota
                </a>
            </div>
        </div>

        <div class="control-group">
            <label>📊 Vista</label>
            <div class="control-buttons">
                <button class="btn btn-secondary" onclick="changeView('grid-1')">1</button>
                <button class="btn btn-secondary" onclick="changeView('grid-2')">2</button>
                <button class="btn btn-secondary" onclick="changeView('grid-3')">3</button>
                <button class="btn btn-secondary" onclick="changeView('grid-4')">4</button>
            </div>
        </div>
    </div>

    @if($notes->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3>No se encontraron notas</h3>
            <p>¡Crea tu primera nota para empezar!</p>
        </div>
    @else
        <div id="notes-list" class="notes-grid grid-3">
            @foreach($notes as $note)
                <div class="note {{ $note->color_class ?? 'bg-default' }}" data-title="{{ $note->title }}" data-content="{{ $note->content }}">
                    <h3 class="note-title">{{ $note->title }}</h3>
                    <div class="note-content">{!! nl2br(e($note->content)) !!}</div>
                    <div class="note-actions">
                        @if(Auth::id() === $note->user_id || Auth::user()->role === 'admin')
                            <a href="{{ route('notes.edit', $note) }}" class="btn btn-secondary">Editar</a>
                            <form action="{{ route('notes.destroy', $note) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta nota?');">Eliminar</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function changeView(gridClass) {
        const notesList = document.getElementById('notes-list');
        // Elimina todas las clases de cuadrícula existentes
        notesList.classList.remove('grid-1', 'grid-2', 'grid-3', 'grid-4');
        // Añade la clase de la cuadrícula seleccionada
        notesList.classList.add(gridClass);
    }
</script>
@endsection