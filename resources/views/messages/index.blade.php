<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $view_title }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                
                {{-- Controles y Botón Volver a Notas --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-6 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <a href="{{ route('notes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Volver a Notas
                    </a>
                    @if(Auth::user()->role === 'vip' || Auth::user()->role === 'admin')
                        <a href="{{ route('messages.create') }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5" 
                           title="Enviar Mensaje">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nuevo
                        </a>
                    @endif
                </div>
                
                <div class="p-6 lg:p-8">
                    {{-- Mensajes de Éxito o Error --}}
                    @if(session('success'))
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-r-lg p-4 mb-6 shadow-sm" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-green-800 font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-r-lg p-4 mb-6 shadow-sm" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-red-800 font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Navegación (Tabs) --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Navegación</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $tabs = [
                                    ['route' => 'messages.index', 'view' => 'inbox', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Recibidos', 'count' => $counts['unread']],
                                    ['route' => 'messages.sent', 'view' => 'sent', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Enviados', 'count' => $counts['sent']],
                                    ['route' => 'messages.trash', 'view' => 'trash', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', 'label' => 'Papelera', 'count' => $counts['trash']]
                                ];
                            @endphp

                            @foreach($tabs as $tab)
                                <a href="{{ route($tab['route']) }}" 
                                   class="inline-flex items-center px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 {{ $current_view === $tab['view'] ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-2 border-gray-200 hover:border-gray-300' }}" 
                                   title="{{ $tab['label'] }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                                    </svg>
                                    {{ $tab['label'] }}
                                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold {{ $current_view === $tab['view'] ? 'bg-white/20' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $tab['count'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if($messages->isEmpty())
                        <div class="text-center py-16 px-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500">No hay mensajes en esta bandeja</p>
                            <p class="text-sm text-gray-400 mt-2">Los mensajes que recibas aparecerán aquí</p>
                        </div>
                    @else
                        
                        {{-- FORMULARIO DE ACCIONES MASIVAS --}}
                        <form id="bulkActionForm" action="{{ route('messages.bulk_action') }}" method="POST">
                            @csrf
                            
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg border-2 border-gray-200">
                                <input type="hidden" name="message_ids" id="bulkMessageIds">
                                
                                <div class="flex items-center gap-3 w-full sm:w-auto">
                                    <select name="action" id="bulkActionSelect" 
                                            class="px-4 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm text-sm font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 bg-white hover:border-gray-400">
                                        <option value="">Seleccionar Acción...</option>
                                        @if ($current_view !== 'trash')
                                            <option value="trash">📥 Mover a Papelera</option>
                                        @else
                                            <option value="restore">↩️ Restaurar</option>
                                            <option value="delete_permanently">🗑️ Eliminar Permanentemente</option>
                                        @endif
                                    </select>
                                    <button type="submit" id="applyBulkAction" 
                                            class="px-5 py-2.5 bg-gradient-to-r from-yellow-400 to-yellow-500 border-2 border-yellow-500 rounded-lg text-gray-800 font-semibold text-sm hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-0.5 disabled:transform-none" 
                                            disabled>
                                        Aplicar
                                    </button>
                                </div>
                                <span id="selectedCount" class="text-sm font-medium text-gray-600 hidden">
                                    <span id="selectedNumber">0</span> seleccionado(s)
                                </span>
                            </div>
                        </form>

                        {{-- Contenedor de Mensajes --}}
                        <div class="space-y-3">
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
                                    $itemClass = $isUnread 
                                        ? 'bg-gradient-to-r from-indigo-50 to-blue-50 border-indigo-300 shadow-md hover:shadow-lg' 
                                        : 'bg-white border-gray-200 hover:border-gray-300 shadow-sm hover:shadow-md';
                                @endphp
                                
                                <div class="p-5 border-2 rounded-xl flex items-center justify-between transition-all duration-200 {{ $itemClass }}">
                                    
                                    <div class="flex items-center space-x-4 flex-grow min-w-0">
                                        {{-- Checkbox --}}
                                        <div class="flex-shrink-0">
                                            <input class="w-5 h-5 text-indigo-600 bg-white rounded-md border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 cursor-pointer transition-all duration-200 message-checkbox hover:border-indigo-400" 
                                                   type="checkbox" 
                                                   data-message-id="{{ $message->id }}" 
                                                   id="message-{{ $message->id }}">
                                        </div>
                                        
                                        {{-- Contenido del Mensaje --}}
                                        <div class="message-content min-w-0 flex-grow">
                                            <div class="flex items-center gap-2 mb-2">
                                                @if ($isUnread)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-sm">
                                                        NUEVO
                                                    </span>
                                                @endif
                                                <small class="text-xs {{ $isUnread ? 'text-indigo-700 font-semibold' : 'text-gray-500' }}">
                                                    @if($isTrashed)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-200 text-gray-700 mr-1">
                                                            {{ $isReceived ? 'Recibido' : 'Enviado' }}
                                                        </span>
                                                    @endif
                                                    <strong>{{ $headerText }}</strong> {{ $relatedUser->name ?? 'Usuario Eliminado' }}
                                                    <span class="mx-1">•</span>
                                                    <span class="text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                                                </small>
                                            </div>
                                            <p class="text-sm {{ $isUnread ? 'font-semibold text-gray-800' : 'text-gray-600' }} truncate" style="max-width: 50vw;">
                                                {{ Str::limit($message->content, 120) }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- ACCIONES INDIVIDUALES --}}
                                    <div class="message-actions flex-shrink-0 flex items-center space-x-2">
                                        @if ($isUnread)
                                            <form action="{{ route('messages.read', $message) }}" method="POST" class="inline-block m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="p-2.5 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200 border-2 border-transparent hover:border-green-200" 
                                                        title="Marcar como leído">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if (!$isTrashed)
                                            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline-block m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 border-2 border-transparent hover:border-red-200" 
                                                        title="Mover a papelera" 
                                                        onclick="return confirm('¿Mover a papelera?');">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('messages.restore', $message->id) }}" method="POST" class="inline-block m-0">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-2.5 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-all duration-200 border-2 border-transparent hover:border-green-200" 
                                                        title="Restaurar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline-block m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200 border-2 border-transparent hover:border-red-200" 
                                                        title="Eliminar Permanentemente" 
                                                        onclick="return confirm('ADVERTENCIA: Vas a ELIMINAR PERMANENTEMENTE este mensaje. ¿Estás seguro?');">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
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
    </div>
    
    {{-- SCRIPT MEJORADO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.message-checkbox');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyButton = document.getElementById('applyBulkAction');
            const bulkActionForm = document.getElementById('bulkActionForm');
            const bulkMessageIdsInput = document.getElementById('bulkMessageIds');
            const selectedCount = document.getElementById('selectedCount');
            const selectedNumber = document.getElementById('selectedNumber');

            function updateBulkActionState() {
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                const actionSelected = bulkActionSelect.value !== "";

                // Actualizar contador
                if (checkedCount > 0) {
                    selectedNumber.textContent = checkedCount;
                    selectedCount.classList.remove('hidden');
                } else {
                    selectedCount.classList.add('hidden');
                }

                // Habilitar/deshabilitar botón
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
            
            bulkActionForm.addEventListener('submit', function(e) {
                const action = bulkActionSelect.value;
                
                const checkedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.getAttribute('data-message-id'))
                    .join(','); 
                    
                bulkMessageIdsInput.value = checkedIds;

                if (action === 'delete_permanently') {
                    if (!confirm('ADVERTENCIA: Vas a ELIMINAR PERMANENTEMENTE los mensajes seleccionados. ¿Estás absolutamente seguro?')) {
                        e.preventDefault();
                    }
                }
            });

            updateBulkActionState();
        });
    </script>
</x-app-layout>