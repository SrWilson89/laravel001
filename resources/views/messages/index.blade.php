@extends('layouts.app')

@section('content')
<div class="container">
    <div class="button-container">
        {{-- Botón para regresar al inicio/notas --}}
        <a href="{{ route('notes.index') }}" class="btn btn-secondary">
            🏠 Volver a Notas
        </a>
        <h1>{{ $view_title }}</h1>
        <span></span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="controls mb-4">
        <div class="control-group">
            <label>Navegación</label>
            <div class="control-buttons">
                {{-- Pestaña: Bandeja de Entrada (Con conteo de no leídos) --}}
                <a href="{{ route('messages.index') }}" class="btn btn-{{ $current_view === 'inbox' ? 'primary' : 'secondary' }}" title="Mensajes Recibidos">
                    ✉️ Recibidos ({{ $counts['unread'] }})
                </a>
                
                {{-- Pestaña: Mensajes Enviados (Con conteo de enviados) --}}
                <a href="{{ route('messages.sent') }}" class="btn btn-{{ $current_view === 'sent' ? 'primary' : 'secondary' }}" title="Mensajes Enviados">
                    📤 Enviados ({{ $counts['sent'] }})
                </a>
                
                {{-- Pestaña: Papelera (Con conteo de papelera) --}}
                <a href="{{ route('messages.trash') }}" class="btn btn-{{ $current_view === 'trash' ? 'primary' : 'secondary' }}" title="Papelera">
                    🗑️ Papelera ({{ $counts['trash'] }})
                </a>

                @if(Auth::user()->role === 'vip' || Auth::user()->role === 'admin')
                    <a href="{{ route('messages.create') }}" class="btn btn-success" title="Enviar Mensaje">
                        + Nuevo
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    @if($messages->isEmpty())
        <div class="alert alert-info">
            No hay mensajes en esta bandeja.
        </div>
    @else
        {{-- Formulario para acciones masivas (eliminar/restaurar) --}}
        <form id="bulkActionForm" action="{{ route('messages.bulk_action') }}" method="POST">
            @csrf
            
            <div class="d-flex justify-content-end mb-3">
                <select name="action" id="bulkActionSelect" class="form-select me-2" style="width: auto;">
                    <option value="">Seleccionar Acción...</option>
                    @if ($current_view !== 'trash')
                        <option value="delete">Mover a Papelera</option>
                    @else
                        <option value="restore">Restaurar</option>
                        <option value="delete">Eliminar Permanentemente (No se recomienda)</option>
                    @endif
                </select>
                <button type="submit" id="applyBulkAction" class="btn btn-warning" disabled>Aplicar</button>
            </div>
            
            <ul class="list-group">
                @foreach ($messages as $message)
                    {{-- 🔑 LÓGICA CLAVE DE CORRECCIÓN AQUÍ --}}
                    @php
                        $isTrashed = $current_view === 'trash';
                        $isReceived = $message->receiver_id === Auth::id();

                        // Determina qué usuario mostrar (Remitente o Receptor)
                        if ($isReceived && $current_view !== 'sent') {
                            $relatedUser = $message->sender;
                            $headerText = 'De:';
                        } elseif ($message->sender_id === Auth::id() && $current_view !== 'inbox') {
                            $relatedUser = $message->receiver;
                            $headerText = 'Para:';
                        } else {
                            // En la papelera o si hay inconsistencias, muestra ambos o el disponible.
                            $relatedUser = $message->sender ?? $message->receiver;
                            $headerText = $isReceived ? 'De:' : 'Para:';
                        }
                        
                        $isUnread = $isReceived && is_null($message->read_at) && !$isTrashed;
                    @endphp
                    {{-- 🔑 FIN LÓGICA CLAVE --}}
                    
                    <li class="list-group-item d-flex align-items-center justify-content-between {{ $isUnread ? 'list-group-item-light fw-bold' : '' }}" style="margin-bottom: 5px; border-radius: 8px;">
                        <div class="form-check me-3">
                            <input class="form-check-input message-checkbox" type="checkbox" name="message_ids[]" value="{{ $message->id }}" id="message-{{ $message->id }}">
                            <label class="form-check-label" for="message-{{ $message->id }}"></label>
                        </div>
                        
                        <div class="message-content flex-grow-1">
                            {{-- Muestra el remitente/receptor dinámicamente --}}
                            <small class="text-muted">
                                @if($isTrashed)
                                    ({{ $isReceived ? 'Recibido' : 'Enviado' }})
                                @endif
                                <strong>{{ $headerText }}</strong> {{ $relatedUser->name ?? 'Usuario Eliminado' }}
                                -
                                {{ $message->created_at->diffForHumans() }}
                            </small>
                            <p class="mb-0 text-truncate" style="max-width: 80%;">
                                @if ($isUnread) 
                                    <span class="badge bg-danger me-1">NUEVO</span>
                                @endif
                                {{ Str::limit($message->content, 100) }}
                            </p>
                        </div>

                        <div class="message-actions d-flex align-items-center">
                            @if ($isUnread)
                                {{-- Botón Marcar como leído --}}
                                <form action="{{ route('messages.read', $message) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-secondary btn-sm me-2" title="Marcar como leído">✅</button>
                                </form>
                            @endif
                            
                            @if (!$isTrashed)
                                {{-- Botón Mover a Papelera (Destroy = Soft Delete) --}}
                                <form action="{{ route('messages.destroy', $message) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Mover a papelera" onclick="return confirm('¿Mover este mensaje a la papelera?');">
                                        ❌
                                    </button>
                                </form>
                            @else
                                {{-- Botón Restaurar --}}
                                <form action="{{ route('messages.restore', $message->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Restaurar">↩️</button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </form>
    @endif
</div>

<script>
    // Lógica para habilitar el botón de acción masiva
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.message-checkbox');
        const bulkActionSelect = document.getElementById('bulkActionSelect');
        const applyButton = document.getElementById('applyBulkAction');
        const bulkActionForm = document.getElementById('bulkActionForm');

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
        
        // Confirmación para la acción de eliminación permanente en la papelera
        bulkActionForm.addEventListener('submit', function(e) {
            const action = bulkActionSelect.value;
            if (action === 'delete' && '{{ $current_view }}' === 'trash') {
                if (!confirm('ADVERTENCIA: Vas a ELIMINAR PERMANENTEMENTE los mensajes seleccionados. ¿Estás absolutamente seguro?')) {
                    e.preventDefault();
                }
            }
        });

        updateBulkActionState(); // Estado inicial
    });
</script>
@endsection
