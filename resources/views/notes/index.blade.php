<x-app-layout>
    {{-- 1. Cabecera --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $view_title ?? '📝 Mis Notas' }}
        </h2>
    </x-slot>

    {{-- 2. Contenido Principal --}}
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Contenedor principal con diseño mejorado --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl"> 
                
                {{-- Mensajes de Éxito o Error --}}
                @if(session('success'))
                    <div class="mx-6 mt-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg shadow-sm flex items-start space-x-3" role="alert">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                
                {{-- 3. Barra de Navegación de Notas (Públicas, Favoritas, Mensajes) y Perfil --}}
                <div class="p-4 sm:px-8 bg-gray-50 border-b border-gray-200 flex justify-between items-center text-sm font-medium">
                    <div class="flex space-x-4">
                        <a href="{{ route('notes.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                            Mis Notas
                        </a>
                        <a href="{{ route('notes.public') }}" class="text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out">
                            Notas Públicas
                        </a>
                        <a href="{{ route('notes.favorites') }}" class="text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out flex items-center">
                            Favoritas
                        </a>
                        <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out flex items-center">
                            Mensajes
                            @php
                                // Ejemplo de cómo podrías obtener un conteo no leído si estuviera disponible en esta vista
                                $unread_count = 0; // Reemplazar con la lógica real del controlador
                            @endphp
                            @if ($unread_count > 0)
                                <span class="ml-1.5 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full font-bold leading-none">{{ $unread_count }}</span>
                            @endif
                        </a>
                    </div>
                    
                    {{-- 4. INFO DE USUARIO LOGUEADO (Avatar, Nombre y Editar Perfil) --}}
                    @if (Auth::check())
                        <div class="flex items-center space-x-4">
                            
                            {{-- Contenedor de Perfil --}}
                            <div class="flex items-center space-x-2 bg-white p-2 rounded-full shadow-inner border border-gray-200">
                                {{-- Imagen de Perfil --}}
                                @php
                                    $initial = strtoupper(substr(Auth::user()->name, 0, 1));
                                @endphp
                                <img class="h-8 w-8 rounded-full object-cover ring-2 ring-indigo-300" 
                                    src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/100x100/A0B2F2/ffffff?text=' . $initial }}" 
                                    alt="{{ Auth::user()->name }}"
                                    onerror="this.onerror=null;this.src='https://placehold.co/100x100/A0B2F2/ffffff?text={{ $initial }}';"
                                />

                                {{-- Nombre de Usuario --}}
                                <span class="text-sm font-bold text-indigo-700 hidden md:inline">{{ Auth::user()->name }}</span>
                                
                                {{-- Botón de Editar Perfil (Boli/Lápiz) --}}
                                <a href="{{ route('profile.edit') }}" title="Editar Perfil" class="text-indigo-500 hover:text-indigo-700 transition duration-150 ease-in-out p-1 rounded-full hover:bg-indigo-50">
                                    {{-- Icono de Boli (Lápiz) --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                            </div>
                            
                            {{-- Enlace de Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold transition duration-150 ease-in-out px-3 py-1 rounded-full border border-red-200 hover:bg-red-50 hidden sm:block">
                                    Cerrar Sesión
                                </button>
                                <button type="submit" title="Cerrar Sesión" class="text-red-600 hover:text-red-800 text-sm font-semibold transition duration-150 ease-in-out p-1 rounded-full hover:bg-red-50 sm:hidden">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                
                {{-- Controles de Creación y Vista --}}
                <div class="p-6 sm:px-8 bg-white border-b border-gray-200 flex justify-between items-center">
                    <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Nueva Nota
                    </a>
                    
                    {{-- Selector de Vista (Grid) --}}
                    <div class="flex space-x-2">
                        <button class="view-btn px-3 py-1 border rounded-lg text-sm transition duration-150 ease-in-out" data-view="grid-1">1 Col</button>
                        <button class="view-btn px-3 py-1 border rounded-lg text-sm transition duration-150 ease-in-out" data-view="grid-2">2 Cols</button>
                        <button class="view-btn px-3 py-1 border rounded-lg text-sm transition duration-150 ease-in-out" data-view="grid-3">3 Cols</button>
                        <button class="view-btn px-3 py-1 border rounded-lg text-sm transition duration-150 ease-in-out" data-view="grid-4">4 Cols</button>
                    </div>
                </div>

                {{-- Listado de Notas --}}
                <div id="notes-list" class="p-6 sm:px-8 pt-0 grid gap-6">
                    @forelse ($notes as $note)
                        {{-- CARD DE LA NOTA --}}
                        <div class="p-6 border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 {{ $note->color ?? 'bg-default' }} flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2 break-words text-gray-800">{{ $note->title }}</h3>
                                
                                {{-- Información del Usuario --}}
                                @if ($note->user_id !== Auth::id())
                                    <p class="text-xs text-gray-500 mb-2">
                                        <span class="font-semibold">Creada por:</span> {{ $note->user->name }}
                                    </p>
                                @endif

                                <p class="text-gray-600 line-clamp-3 mb-4 break-words">{{ $note->content }}</p>
                            </div>

                            {{-- TAGS --}}
                            <div class="flex flex-wrap gap-2 mb-4">
                                @forelse ($note->tags as $tag)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 shadow-sm transition duration-150 ease-in-out hover:bg-indigo-200">
                                        {{ $tag->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400">Sin etiquetas</span>
                                @endforelse
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="flex justify-end space-x-3 mt-2">
                                {{-- Botón Ver --}}
                                <a href="{{ route('notes.show', $note) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold transition duration-150 ease-in-out flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Ver
                                </a>

                                {{-- Botones de Edición y Eliminación (Solo si el usuario puede actualizar/eliminar) --}}
                                @can('update', $note)
                                    <a href="{{ route('notes.edit', $note) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition duration-150 ease-in-out flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Editar
                                    </a>
                                @endcan

                                @can('delete', $note)
                                    {{-- NOTA: Se reemplazará el confirm() con un modal personalizado en el futuro. --}}
                                    <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta nota?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold transition duration-150 ease-in-out flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay notas</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Empieza creando una nueva nota para organizar tus ideas.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Crear Nueva Nota
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Script para gestionar la vista de la cuadrícula --}}
                <script>
                    function changeView(gridClass) {
                        const notesList = document.getElementById('notes-list');
                        if (!notesList) return;

                        // Limpia todas las clases de cuadrícula existentes
                        notesList.classList.remove('sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4');
                        
                        // Añade la clase de cuadrícula basada en la selección
                        if (gridClass === 'grid-1') {
                            notesList.classList.add('sm:grid-cols-1');
                            notesList.classList.add('md:grid-cols-1'); // Asegura 1 columna en tablet y móvil
                        } else if (gridClass === 'grid-2') {
                            notesList.classList.add('md:grid-cols-2');
                            notesList.classList.add('sm:grid-cols-1'); // Asegura 1 columna en móvil
                        } else if (gridClass === 'grid-3') {
                            notesList.classList.add('lg:grid-cols-3');
                            notesList.classList.add('md:grid-cols-2');
                            notesList.classList.add('sm:grid-cols-1');
                        } else if (gridClass === 'grid-4') {
                            notesList.classList.add('xl:grid-cols-4');
                            notesList.classList.add('lg:grid-cols-3');
                            notesList.classList.add('md:grid-cols-2');
                            notesList.classList.add('sm:grid-cols-1');
                        }
                        
                        // Resalta el botón activo
                        document.querySelectorAll('.view-btn').forEach(btn => {
                            btn.classList.remove('bg-blue-100', 'border-blue-500', 'text-blue-700');
                            btn.classList.add('bg-white', 'border-gray-200', 'text-gray-700');
                        });

                        const activeBtn = document.querySelector(`.view-btn[data-view="${gridClass}"]`);
                        if (activeBtn) {
                            activeBtn.classList.remove('bg-white', 'border-gray-200', 'text-gray-700');
                            activeBtn.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-700');
                        }
                        
                        localStorage.setItem('noteViewPreference', gridClass);
                    }
                    
                    document.addEventListener('DOMContentLoaded', () => {
                        const notesList = document.getElementById('notes-list');
                        if (notesList) {
                            // Inicializa la lista como grid (solo si no tiene estilos previos)
                            if (!notesList.classList.contains('grid')) {
                                notesList.classList.add('grid');
                            }
                            
                            // Asegura la configuración inicial de la cuadrícula
                            const savedView = localStorage.getItem('noteViewPreference') || 'grid-3';
                            
                            // Aplica la vista guardada inmediatamente, esto también maneja el resalte del botón
                            changeView(savedView);
                            
                            const buttons = document.querySelectorAll('.view-btn');
                            buttons.forEach(btn => {
                                btn.addEventListener('click', (event) => {
                                    changeView(event.currentTarget.dataset.view);
                                });
                            });
                        }
                    });
                </script>

            </div>
        </div>
    </div>
    
    {{-- Definición de clases de color de notas personalizadas (pastel y legible) --}}
    <style>
        .bg-default { background-color: #ffffff !important; } /* Blanco */
        .bg-red { background-color: #fee2e2 !important; } /* Rosa muy claro/Rojo */
        .bg-orange-red { background-color: #ffe6d3 !important; } /* Melocotón claro */
        .bg-orange { background-color: #ffedd5 !important; } /* Naranja claro */
        .bg-yellow-orange { background-color: #fff9d5 !important; } /* Amarillo-Naranja */
        .bg-yellow { background-color: #fef9c3 !important; } /* Amarillo muy claro */
        .bg-yellow-green { background-color: #eaffd5 !important; } /* Lima muy claro */
        .bg-green { background-color: #dcfce7 !important; } /* Verde muy claro */
        .bg-blue-green { background-color: #d5fff4 !important; } /* Turquesa claro */
        .bg-blue { background-color: #dbeafe !important; } /* Azul muy claro */
        .bg-blue-violet { background-color: #e0d5ff !important; } /* Lavanda claro */
        .bg-violet { background-color: #ede9fe !important; } /* Violeta muy claro */
        .bg-red-violet { background-color: #ffd5f5 !important; } /* Magenta claro */
    </style>
</x-app-layout>
