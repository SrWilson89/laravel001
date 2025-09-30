<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $view_title }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                
                {{-- Controles y Botón Volver a Notas --}}
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <a href="{{ route('notes.index') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition">
                        🏠 Volver a Notas
                    </a>
                    @if(Auth::user()->role === 'vip' || Auth::user()->role === 'admin')
                        <a href="{{ route('messages.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition" title="Enviar Mensaje">
                            + Nuevo
                        </a>
                    @endif
                </div>
                
                {{-- Mensajes de Éxito o Error --}}
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
                
                {{-- Navegación (Tabs) --}}
                <div class="mb-6 border-b border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Navegación</label>
                    <div class="flex space-x-2">
                        @php
                            $baseClass = 'px-4 py-2 rounded-md transition';
                            $activeClass = 'bg-indigo-600 text-white shadow-lg';
                            $inactiveClass = 'bg-gray-100 border border-gray-300 text-gray-700 hover:bg-gray-200';
                        @endphp

                        <a href="{{ route('messages.index') }}" 
                           class="{{ $baseClass }} {{ $current_view === 'inbox' ? $activeClass : $inactiveClass }}" title="Mensajes Recibidos">
                            ✉️ Recibidos ({{ $counts['unread'] }})
                        </a>
                        
                        <a href="{{ route('messages.sent') }}" 
                           class="{{ $baseClass }} {{ $current_view === 'sent' ? $activeClass : $inactiveClass }}" title="Mensajes Enviados">
                            📤 Enviados ({{ $counts['sent'] }})
                        </a>
                        
                        <a href="{{ route('messages.trash') }}" 
                           class="{{ $baseClass }} {{ $current_view === 'trash' ? $activeClass : $inactiveClass }}" title="Papelera">
                            🗑️ Papelera ({{ $counts['trash'] }})
                        </a>
                    </div>
                </div>

                @if($messages->isEmpty())
                    <div class="text-center p-10 bg-gray-50 rounded-lg text-gray-500">
                        No hay mensajes en esta bandeja.
                    </div>
                @else
                    
                    {{-- FORMULARIO DE ACCIONES MASIVAS: AHORA SOLO ENVUELVE LOS CONTROLES --}}
                    <form id="bulkActionForm" action="{{ route('messages.bulk_action') }}" method="POST">
                        @csrf
                        
                        <div class="flex justify-end items-center mb-4 space-x-2">
                            {{-- Campo oculto para IDs (se llena con JS) --}}
                            <input type="hidden" name="message_ids" id="bulkMessageIds">
                            
                            <select name="action" id="bulkActionSelect" class="border-gray-300 rounded-md shadow-sm text-sm" style="width: auto;">
                                <option value="">Seleccionar Acción...</option>
                                @if ($current_view !== 'trash')
                                    <option value="trash">Mover a Papelera</option>
                                @else
                                    <option value="restore">Restaurar</option>
                                    <option value="delete_permanently">Eliminar Permanentemente</option>
                                @endif
                            </select>
                            <button type="submit" id="applyBulkAction" class="px-3 py-2 bg-yellow-400 border border-yellow-500 rounded-md text-gray-800 text-sm hover:bg-yellow-500 transition disabled:opacity-50" disabled>Aplicar</button>
                        </div>
                    </form>
                    {{-- ¡AQUÍ TERMINA EL FORMULARIO MASIVO! La tabla de mensajes está ahora fuera del formulario. --}}

                    {{-- Contenedor de Mensajes (antes list-group) --}}
                    <div class="space-y-2">
                        @foreach ($messages as $message)
                            @php
                                $isTrashed = $current_view === 'trash';
                                $isReceived = $message->receiver_id === Auth::id();

                                if ($isReceived && $current_view !== 'sent') {
                                    $relatedUser = $message->sender;
                                    $headerText = 'De:';
                                } elseif ($message->sender_id === Auth::id() && $current_view !== 'inbox') {
                                    $relatedUser = $message->receiver;
                                    $headerText = 'Para:';
                                } else {
                                    $relatedUser = $message->sender ?? $message->receiver;
                                    $headerText = $isReceived ? 'De:' : 'Para:';
                                }
                                
                                $isUnread = $isReceived && is_null($message->read_at) && !$isTrashed;
                                $itemClass = $isUnread ? 'bg-indigo-50 border-indigo-200 font-bold' : 'bg-white border-gray-200';
                            @endphp
                            
                            <div class="p-4 border shadow-sm rounded-lg flex items-center justify-between transition hover:shadow-md {{ $itemClass }}">
                                
                                <div class="flex items-center space-x-4 flex-grow min-w-0">
                                    {{-- Checkbox (Fuera del form masivo, los IDs se recogen con JS) --}}
                                    <div class="flex-shrink-0">
                                        <input class="w-4 h-4 text-indigo-600 bg-gray-100 rounded border-gray-300 focus:ring-indigo-500 message-checkbox" type="checkbox" data-message-id="{{ $message->id }}" id="message-{{ $message->id }}">
                                    </div>
                                    
                                    {{-- Contenido del Mensaje --}}
                                    <div class="message-content min-w-0 flex-grow">
                                        <small class="text-xs {{ $isUnread ? 'text-indigo-600' : 'text-gray-500' }} block mb-1">
                                            @if($isTrashed)
                                                ({{ $isReceived ? 'Recibido' : 'Enviado' }})
                                            @endif
                                            <strong>{{ $headerText }}</strong> {{ $relatedUser->name ?? 'Usuario Eliminado' }}
                                            -
                                            {{ $message->created_at->diffForHumans() }}
                                        </small>
                                        <p class="mb-0 text-sm truncate" style="max-width: 40vw;">
                                            @if ($isUnread) 
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 me-1">NUEVO</span>
                                            @endif
                                            {{ Str::limit($message->content, 100) }}
                                        </p>
                                    </div>
                                </div>

                                {{-- ACCIONES INDIVIDUALES (Ahora en sus PROPIOS formularios) --}}
                                <div class="message-actions flex-shrink-0 flex items-center space-x-2">
                                    @if ($isUnread)
                                        <form action="{{ route('messages.read', $message) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-green-600 hover:bg-gray-100 rounded-full transition" title="Marcar como leído">✅</button>
                                        </form>
                                    @endif
                                    
                                    @if (!$isTrashed)
                                        {{-- Mover a Papelera (Destroy = Soft Delete) --}}
                                        <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-full transition" title="Mover a papelera" onclick="return confirm('¿Mover a papelera?');">
                                                ❌
                                            </button>
                                        </form>
                                    @else
                                        {{-- Botón Restaurar --}}
                                        <form action="{{ route('messages.restore', $message->id) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            <button type="submit" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-full transition" title="Restaurar">↩️</button>
                                        </form>
                                        {{-- Botón Eliminar Permanente --}}
                                        <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition" title="Eliminar Permanentemente" onclick="return confirm('ADVERTENCIA: Vas a ELIMINAR PERMANENTEMENTE este mensaje. ¿Estás seguro?');">
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- SCRIPT CORREGIDO PARA MANEJAR LA ACCIÓN MASIVA FUERA DEL FORMULARIO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.message-checkbox');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyButton = document.getElementById('applyBulkAction');
            const bulkActionForm = document.getElementById('bulkActionForm');
            const bulkMessageIdsInput = document.getElementById('bulkMessageIds');

            function updateBulkActionState() {
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                const actionSelected = bulkActionSelect.value !== "";

                if (checkedCount > 0 && actionSelected) {
                    applyButton.removeAttribute('disabled');
                } else {
                    applyButton.setAttribute('disabled', 'disabled');
                }
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActionState);
            });

            bulkActionSelect.addEventListener('change', updateBulkActionState);
            
            // Manejador de envío del formulario masivo
            bulkActionForm.addEventListener('submit', function(e) {
                const action = bulkActionSelect.value;
                
                // 1. Recoger los IDs de los checkboxes marcados
                const checkedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.getAttribute('data-message-id'))
                    .join(','); 
                    
                bulkMessageIdsInput.value = checkedIds;

                // 2. Confirmación para la acción de eliminación permanente
                if (action === 'delete_permanently') {
                    if (!confirm('ADVERTENCIA: Vas a ELIMINAR PERMANENTEMENTE los mensajes seleccionados. ¿Estás absolutamente seguro?')) {
                        e.preventDefault();
                    }
                }
            });

            updateBulkActionState(); // Estado inicial
        });
    </script>
</x-app-layout>