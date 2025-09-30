<x-app-layout>
    {{-- 1. Cabecera (Se muestra en la parte superior del layout) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $view_title ?? '📝 Mis Notas' }}
        </h2>
    </x-slot>

    {{-- 2. Contenido Principal (Utiliza las clases de espaciado y centrado de Breeze) --}}
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Contenedor blanco con sombra y padding --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8"> 
                
                {{-- Mensajes de Éxito o Error (Clases Tailwind para Alerts) --}}
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            
                {{-- Controles (Autenticación, Acciones, Vista) --}}
                <div class="space-y-6">
                    
                    {{-- GRUPO: 🔑 Autenticación --}}
                    <div class="border-b pb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">🔑 Autenticación</label>
                        <div class="flex flex-wrap items-center space-x-2">
                            @auth
                                <span class="text-sm font-medium text-gray-600 mr-4">Hola, {{ Auth::user()->name }}</span>
                                
                                {{-- ✉️ Mensajes --}}
                                <a href="{{ route('messages.index') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition" title="Mensajes">✉️</a>
                                {{-- 👤 Perfil --}}
                                <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition" title="Perfil">👤 Perfil</a>
                                
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    {{-- 🚪 Cerrar Sesión --}}
                                    <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition" title="Cerrar Sesión">🚪 Cerrar Sesión</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">🗝️ Login</a>
                                <a href="{{ route('register') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">🔑 Registro</a>
                            @endauth
                        </div>
                    </div>

                    {{-- GRUPO: 🚀 Acciones --}}
                    <div class="border-b pb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">🚀 Acciones</label>
                        <div class="flex flex-wrap space-x-2">
                            <a href="{{ route('notes.index') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition" title="Todas las Notas">🏠 Todas</a>
                            <a href="{{ route('notes.favorites') }}" class="px-4 py-2 bg-yellow-100 border border-yellow-300 rounded-md text-yellow-700 hover:bg-yellow-200 transition" title="Notas Favoritas">❤️ Favoritas</a>
                            <a href="{{ route('notes.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition" title="Nueva Nota">➕ Nueva Nota</a>
                        </div>
                    </div>

                    {{-- GRUPO: 📊 Vista --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">📊 Vista</label>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition" onclick="changeView('grid-1')" title="1 Columna (Lista)">1</button>
                            <button class="px-3 py-1 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition" onclick="changeView('grid-2')" title="2 Columnas">2</button>
                            <button class="px-3 py-1 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition" onclick="changeView('grid-3')" title="3 Columnas">3</button>
                            <button class="px-3 py-1 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition" onclick="changeView('grid-4')" title="4 Columna">4</button>
                        </div>
                    </div>
                </div>
                
                <hr class="my-8 border-gray-200">

                {{-- Listado de Notas --}}
                @if(empty($notes) || $notes->isEmpty())
                    <div class="text-center p-10 bg-gray-50 rounded-lg">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-16 h-16 mx-auto text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">{{ $view_title ?? 'No se encontraron notas' }}</h3>
                        @if(!isset($view_title) || $view_title == '📝 Mis Notas')
                            <p class="mt-1 text-sm text-gray-500">¡Crea tu primera nota para empezar!</p>
                        @endif
                    </div>
                @else
                    {{-- 3. Contenedor de notas con clases base de Tailwind Grid --}}
                    <div id="notes-list" class="notes-grid grid gap-6 mt-8"> 
                        @foreach($notes as $note)
                            {{-- **LÍNEA CORREGIDA:** Se eliminó bg-white para evitar el conflicto. --}}
                            <div class="note p-5 rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out {{ $note->color_class ?? 'bg-default border border-gray-200' }}" 
                                 data-title="{{ $note->title }}" data-content="{{ $note->content }}">
                                
                                <h3 class="note-title text-xl font-bold mb-2">{{ $note->title }}</h3>
                                <div class="note-content text-gray-700 text-sm">{!! nl2br(e($note->content)) !!}</div>
                                
                                @auth
                                <div class="note-actions mt-4 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                    
                                    {{-- **MARCADOR FUNCIONAL (CORAZÓN / FAVORITO)** --}}
                                    @php
                                        $isLiked = $note->likes->contains(Auth::id()); 
                                        $likeRoute = $isLiked ? route('notes.unlike', $note) : route('notes.like', $note);
                                    @endphp

                                    <form action="{{ $likeRoute }}" method="POST" class="m-0">
                                        @csrf
                                        @if ($isLiked)
                                            @method('DELETE')
                                        @endif
                                        {{-- Botón de Corazón --}}
                                        <button type="submit" class="p-1 rounded-full text-lg hover:bg-gray-100 transition" title="{{ $isLiked ? 'Quitar Favorito' : 'Marcar Favorito' }}">
                                            {!! $isLiked ? '❤️' : '🤍' !!}
                                        </button>
                                    </form>
                                    
                                    {{-- Lógica de Editar y Eliminar --}}
                                    
                                    @if($note->user_id === Auth::id())
                                        {{-- 📝 Editar --}}
                                        <a href="{{ route('notes.edit', $note) }}" class="p-1 rounded-full text-base text-gray-500 hover:bg-gray-100 transition" title="Editar">
                                            📝
                                        </a>
                                    @endif
                                    
                                    @if($note->user_id === Auth::id() || (Auth::user()->role === 'admin'))
                                        {{-- 🗑️ Eliminar (Permanente) --}}
                                        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-full text-base text-red-500 hover:bg-red-100 transition" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar esta nota de forma permanente?');">
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

                {{-- 4. Script JS Corregido para usar Clases de Tailwind --}}
                <script>
                    function changeView(gridClass) {
                        const notesList = document.getElementById('notes-list');
                        
                        // Mapeo de la clase del botón a la clase de Tailwind Grid (Responsive)
                        const classMap = {
                            'grid-1': 'grid-cols-1', 
                            'grid-2': 'sm:grid-cols-2',
                            'grid-3': 'md:grid-cols-3',
                            'grid-4': 'lg:grid-cols-4' 
                        };
                        
                        // Clases de Tailwind que definen las columnas (para poder eliminarlas)
                        const allGridClasses = ['grid-cols-1', 'sm:grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-4'];

                        // 1. Elimina todas las clases de cuadrícula responsivas
                        allGridClasses.forEach(cls => notesList.classList.remove(cls));
                        
                        // 2. Añade la clase base (grid-cols-1) y la clase específica
                        if (gridClass === 'grid-1') {
                             notesList.classList.add(classMap['grid-1']);
                        } else if (classMap[gridClass]) {
                             // Para 2, 3 o 4 columnas, añadimos la clase base de 1 columna 
                             // y la clase responsive que indica el número de columnas.
                             notesList.classList.add('grid-cols-1', classMap[gridClass]);
                        }
                    }
                    
                    // Inicializa la vista por defecto (grid-3) al cargar la página
                    document.addEventListener('DOMContentLoaded', () => {
                        const notesList = document.getElementById('notes-list');
                        if (notesList) {
                             // Asegura que el contenedor es un grid
                             notesList.classList.add('grid');
                             changeView('grid-3');
                        }
                    });
                </script>

            </div>
        </div>
    </div>
</x-app-layout>