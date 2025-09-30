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
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mx-6 mt-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg shadow-sm flex items-start space-x-3" role="alert">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif
            
                {{-- Panel de Controles con diseño mejorado --}}
                <div class="p-6 lg:p-8 space-y-6">
                    
                    {{-- GRUPO: 🔐 Autenticación --}}
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-5 border border-gray-100">
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Autenticación
                        </label>
                        <div class="flex flex-wrap items-center gap-3">
                            @auth
                                
                                {{-- 📸 AVATAR EDITABLE --}}
                                @php
                                    $photoUrl = Auth::user()->profile_photo_path 
                                                ? \Illuminate\Support\Facades\Storage::url(Auth::user()->profile_photo_path) 
                                                : 'https://placehold.co/40x40/4f46e5/ffffff?text=' . strtoupper(substr(Auth::user()->name, 0, 1));
                                @endphp

                                <a href="{{ route('profile.edit') }}" 
                                    class="group relative block h-11 w-11 rounded-full ring-2 ring-indigo-400 hover:ring-indigo-600 transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg" 
                                    title="Editar Perfil de {{ Auth::user()->name }}">
                                    
                                    <img src="{{ $photoUrl }}" 
                                        alt="{{ Auth::user()->name }}" 
                                        class="h-full w-full object-cover rounded-full">

                                    <span class="absolute -bottom-1 -right-1 h-5 w-5 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-xs shadow-lg border-2 border-white group-hover:scale-110 transition-transform duration-200">
                                        ✏️
                                    </span>
                                </a>

                                <span class="text-sm font-semibold text-gray-700 px-2">Hola, {{ Auth::user()->name }}</span>
                                
                                {{-- ✉️ Mensajes --}}
                                <a href="{{ route('messages.index') }}" 
                                   class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm hover:shadow font-medium text-sm flex items-center gap-2" 
                                   title="Mensajes">
                                    <span>✉️</span>
                                    <span class="hidden sm:inline">Mensajes</span>
                                </a>
                                
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" 
                                        class="px-5 py-2.5 bg-custom-red border border-transparent rounded-lg font-bold text-sm !text-black uppercase tracking-wider transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2.5 min-w-max"
                                        title="Cerrar Sesión">
                                        <span class="text-base">🚪</span>
                                        <span class="hidden sm:inline">Cerrar Sesión</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow font-semibold text-sm">
                                    🗝️ Login
                                </a>
                                <a href="{{ route('register') }}" 
                                   class="px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 border border-transparent rounded-lg text-white hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg font-semibold text-sm">
                                    📝 Registro
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- GRUPO: 🚀 Acciones --}}
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 border border-indigo-100">
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                            </svg>
                            Acciones
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('notes.index') }}" 
                               class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm hover:shadow font-medium text-sm flex items-center gap-2" 
                               title="Todas las Notas">
                                <span>🏠</span>
                                <span>Todas</span>
                            </a>
                            <a href="{{ route('notes.favorites') }}" 
                               class="px-4 py-2 bg-gradient-to-r from-yellow-100 to-amber-100 border border-yellow-200 rounded-lg text-yellow-800 hover:from-yellow-200 hover:to-amber-200 hover:border-yellow-300 transition-all duration-200 shadow-sm hover:shadow font-medium text-sm flex items-center gap-2" 
                               title="Notas Favoritas">
                                <span>❤️</span>
                                <span>Favoritas</span>
                            </a>
                            <a href="{{ route('notes.create') }}" 
                               class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center gap-2" 
                               title="Nueva Nota">
                                <span>➕</span>
                                <span>Nueva Nota</span>
                            </a>
                        </div>
                    </div>

                    {{-- GRUPO: 📊 Vista --}}
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-5 border border-blue-100">
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            Vista
                        </label>
                        <div class="flex gap-2">
                            <button class="view-btn px-4 py-2 bg-white border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-blue-50 hover:border-blue-400 transition-all duration-200 font-semibold shadow-sm hover:shadow" 
                                    onclick="changeView('grid-1')" 
                                    title="1 Columna (Lista)">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button class="view-btn px-4 py-2 bg-white border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-blue-50 hover:border-blue-400 transition-all duration-200 font-semibold shadow-sm hover:shadow" 
                                    onclick="changeView('grid-2')" 
                                    title="2 Columnas">
                                <div class="flex gap-1">
                                    <div class="w-2 h-5 bg-current rounded"></div>
                                    <div class="w-2 h-5 bg-current rounded"></div>
                                </div>
                            </button>
                            <button class="view-btn px-4 py-2 bg-white border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-blue-50 hover:border-blue-400 transition-all duration-200 font-semibold shadow-sm hover:shadow" 
                                    onclick="changeView('grid-3')" 
                                    title="3 Columnas">
                                <div class="flex gap-1">
                                    <div class="w-1.5 h-5 bg-current rounded"></div>
                                    <div class="w-1.5 h-5 bg-current rounded"></div>
                                    <div class="w-1.5 h-5 bg-current rounded"></div>
                                </div>
                            </button>
                            <button class="view-btn px-4 py-2 bg-white border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-blue-50 hover:border-blue-400 transition-all duration-200 font-semibold shadow-sm hover:shadow" 
                                    onclick="changeView('grid-4')" 
                                    title="4 Columnas">
                                <div class="flex gap-0.5">
                                    <div class="w-1 h-5 bg-current rounded"></div>
                                    <div class="w-1 h-5 bg-current rounded"></div>
                                    <div class="w-1 h-5 bg-current rounded"></div>
                                    <div class="w-1 h-5 bg-current rounded"></div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 lg:px-8">
                    <hr class="border-gray-200">
                </div>

                {{-- Listado de Notas --}}
                <div class="p-6 lg:p-8">
                    @if(empty($notes) || $notes->isEmpty())
                        <div class="text-center p-12 bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-20 h-20 mx-auto text-gray-300 mb-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">{{ $view_title ?? 'No se encontraron notas' }}</h3>
                            @if(!isset($view_title) || $view_title == '📝 Mis Notas')
                                <p class="text-gray-500 mb-6">¡Crea tu primera nota para empezar!</p>
                                <a href="{{ route('notes.create') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <span>➕</span>
                                    <span>Crear Primera Nota</span>
                                </a>
                            @endif
                        </div>
                    @else
                        <div id="notes-list" class="notes-grid grid gap-5"> 
                            @foreach($notes as $note)
                                <div class="note group p-6 rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 {{ $note->color_class ?? 'bg-white border-2 border-gray-100' }} relative overflow-hidden" 
                                        data-title="{{ $note->title }}" data-content="{{ $note->content }}">
                                    
                                    {{-- Efecto de brillo sutil en hover --}}
                                    <div class="absolute inset-0 bg-gradient-to-br from-white/0 to-white/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    
                                    <div class="relative z-10">
                                        <h3 class="note-title text-xl font-bold mb-3 text-gray-800 group-hover:text-gray-900 transition-colors line-clamp-2">{{ $note->title }}</h3>
                                        <div class="note-content text-gray-600 text-sm leading-relaxed line-clamp-6 mb-4">{!! nl2br(e($note->content)) !!}</div>
                                        
                                        @auth
                                        <div class="note-actions pt-4 border-t border-gray-200/50 flex items-center gap-2">
                                            
                                            {{-- Marcador Funcional --}}
                                            @php
                                                $isLiked = $note->likes->contains(Auth::id()); 
                                                $likeRoute = $isLiked ? route('notes.unlike', $note) : route('notes.like', $note);
                                            @endphp

                                            <form action="{{ $likeRoute }}" method="POST" class="m-0">
                                                @csrf
                                                @if ($isLiked)
                                                    @method('DELETE')
                                                @endif
                                                <button type="submit" 
                                                        class="p-2 rounded-lg text-lg hover:bg-white/70 transition-all duration-200 transform hover:scale-110 {{ $isLiked ? 'text-red-500' : 'text-gray-400' }}" 
                                                        title="{{ $isLiked ? 'Quitar Favorito' : 'Marcar Favorito' }}">
                                                    {!! $isLiked ? '❤️' : '🤍' !!}
                                                </button>
                                            </form>
                                            
                                            @if($note->user_id === Auth::id())
                                                <a href="{{ route('notes.edit', $note) }}" 
                                                   class="p-2 rounded-lg text-base text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 transform hover:scale-110" 
                                                   title="Editar">
                                                    📝
                                                </a>
                                            @endif
                                            
                                            @if($note->user_id === Auth::id() || (Auth::user()->role === 'admin'))
                                                <form action="{{ route('notes.destroy', $note) }}" method="POST" class="m-0 ml-auto">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2 rounded-lg text-base text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all duration-200 transform hover:scale-110" 
                                                            title="Eliminar" 
                                                            onclick="return confirm('¿Estás seguro de que quieres eliminar esta nota de forma permanente?');">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <script>
                    function changeView(gridClass) {
                        const notesList = document.getElementById('notes-list');
                        const buttons = document.querySelectorAll('.view-btn');
                        
                        const classMap = {
                            'grid-1': 'grid-cols-1', 
                            'grid-2': 'sm:grid-cols-2',
                            'grid-3': 'md:grid-cols-3',
                            'grid-4': 'lg:grid-cols-4' 
                        };
                        
                        const allGridClasses = ['grid-cols-1', 'sm:grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-4'];

                        allGridClasses.forEach(cls => notesList.classList.remove(cls));
                        
                        buttons.forEach(btn => {
                            btn.classList.remove('bg-blue-100', 'border-blue-500', 'text-blue-700');
                            btn.classList.add('bg-white', 'border-gray-200', 'text-gray-700');
                        });
                        
                        if (gridClass === 'grid-1') {
                             notesList.classList.add(classMap['grid-1']);
                        } else if (classMap[gridClass]) {
                             notesList.classList.add('grid-cols-1', classMap[gridClass]);
                        }
                        
                        event.currentTarget.classList.remove('bg-white', 'border-gray-200', 'text-gray-700');
                        event.currentTarget.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-700');
                        
                        localStorage.setItem('noteViewPreference', gridClass);
                    }
                    
                    document.addEventListener('DOMContentLoaded', () => {
                        const notesList = document.getElementById('notes-list');
                        if (notesList) {
                             notesList.classList.add('grid');
                             const savedView = localStorage.getItem('noteViewPreference') || 'grid-3';
                             changeView(savedView);
                             
                             const buttons = document.querySelectorAll('.view-btn');
                             buttons.forEach((btn, idx) => {
                                 const viewTypes = ['grid-1', 'grid-2', 'grid-3', 'grid-4'];
                                 if (viewTypes[idx] === savedView) {
                                     btn.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-700');
                                 }
                             });
                        }
                    });
                </script>

            </div>
        </div>
    </div>
</x-app-layout>