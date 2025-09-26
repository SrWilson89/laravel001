@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Muestra los mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="header">
        {{-- Muestra el título dinámico, o el predeterminado --}}
        <h1>{!! $view_title ?? '📝 Mis Notas' !!}</h1>
        <p>Organiza tus ideas de forma elegante</p>
    </div>

    <div class="controls">
        <div class="control-group">
            <label>🔑 Autenticación</label>
            <div class="control-buttons">
                @auth
                    <span style="align-self: center; margin-right: 10px;">Hola, {{ Auth::user()->name }}</span>
                    {{-- 👤 Perfil --}}
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary" title="Perfil">👤 Perfil</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        {{-- 🚪 Cerrar Sesión --}}
                        <button type="submit" class="btn btn-secondary" title="Cerrar Sesión">🚪 Cerrar Sesión</button>
                    </form>
                @else
                    {{-- 🗝️ Login/🔑 Registro --}}
                    <a href="{{ route('login') }}" class="btn btn-secondary">🗝️ Login</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">🔑 Registro</a>
                @endauth
            </div>
        </div>

        <div class="control-group">
            <label>🚀 Acciones</label>
            <div class="control-buttons">
                {{-- 🏠 Todas las Notas --}}
                <a href="{{ route('notes.index') }}" class="btn btn-secondary" title="Todas las Notas">
                    🏠 Todas
                </a>
                {{-- ❤️ Notas Favoritas (Acceso al filtro) --}}
                <a href="{{ route('notes.favorites') }}" class="btn btn-secondary" title="Notas Favoritas">
                    ❤️ Favoritas
                </a>
                {{-- ➕ Nueva Nota --}}
                <a href="{{ route('notes.create') }}" class="btn btn-primary" title="Nueva Nota">
                    ➕ Nueva Nota
                </a>
            </div>
        </div>

        <div class="control-group">
            <label>📊 Vista</label>
            <div class="control-buttons">
                {{-- Botones de Columna con NÚMEROS --}}
                <button class="btn btn-secondary" onclick="changeView('grid-1')" title="1 Columna (Lista)">1</button>
                <button class="btn btn-secondary" onclick="changeView('grid-2')" title="2 Columnas">2</button>
                <button class="btn btn-secondary" onclick="changeView('grid-3')" title="3 Columnas">3</button>
                <button class="btn btn-secondary" onclick="changeView('grid-4')" title="4 Columna">4</button>
            </div>
        </div>
    </div>
    
    @if($notes->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3>{{ $view_title ?? 'No se encontraron notas' }}</h3>
            @if(!isset($view_title) || $view_title == '📝 Mis Notas')
                <p>¡Crea tu primera nota para empezar!</p>
            @endif
        </div>
    @else
        <div id="notes-list" class="notes-grid grid-3">
            @foreach($notes as $note)
                <div class="note {{ $note->color_class ?? 'bg-default' }}" data-title="{{ $note->title }}" data-content="{{ $note->content }}">
                    <h3 class="note-title">{{ $note->title }}</h3>
                    <div class="note-content">{!! nl2br(e($note->content)) !!}</div>
                    
                    @auth
                    <div class="note-actions">
                        
                        {{-- MARCADOR FUNCIONAL (CORAZÓN) --}}
                        @php
                            $isLiked = $note->likes->contains(Auth::id()); 
                            $likeRoute = $isLiked ? route('notes.unlike', $note) : route('notes.like', $note);
                        @endphp

                        <form action="{{ $likeRoute }}" method="POST" style="margin: 0;">
                            @csrf
                            @if ($isLiked)
                                @method('DELETE')
                            @endif
                            <button type="submit" class="btn btn-icon btn-like" title="{{ $isLiked ? 'Quitar Favorito' : 'Marcar Favorito' }}">
                                {{-- ❤️ Corazón lleno o 🤍 Corazón vacío --}}
                                {!! $isLiked ? '❤️' : '🤍' !!}
                            </button>
                        </form>
                        
                        {{-- Lógica de Editar y Eliminar --}}
                        
                        {{-- El usuario PUEDE EDITAR solo si es el dueño --}}
                        @if($note->user_id === Auth::id())
                            {{-- 📝 Editar --}}
                            <a href="{{ route('notes.edit', $note) }}" class="btn btn-secondary" title="Editar">
                                📝
                            </a>
                        @endif
                        
                        {{-- El usuario PUEDE ELIMINAR si es el dueño O si es admin --}}
                        @if($note->user_id === Auth::id() || (Auth::user()->role === 'admin'))
                            {{-- 🗑️ Eliminar --}}
                            <form action="{{ route('notes.destroy', $note) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar esta nota?');">
                                    🗑️
                                </button>
                            </form>
                        @endif
                    </div>
                    @endauth
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function changeView(gridClass) {
        const notesList = document.getElementById('notes-list');
        // Elimina todas las clases de cuadrícula existentes (grid-1, grid-2, grid-3, grid-4)
        notesList.classList.remove('grid-1', 'grid-2', 'grid-3', 'grid-4');
        // Añade la clase de la cuadrícula seleccionada
        notesList.classList.add(gridClass);
    }
</script>
@endsection