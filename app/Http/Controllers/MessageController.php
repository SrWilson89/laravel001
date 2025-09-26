<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Helper para obtener los conteos de navegación (Enviados y Papelera).
     */
    private function getNavigationCounts()
    {
        $userId = Auth::id();

        $counts = [
            // Bandeja de entrada (Mensajes NO eliminados)
            'inbox' => Message::where('receiver_id', $userId)
                              ->whereNull('deleted_at')
                              ->count(),
            // Mensajes Enviados (Mensajes NO eliminados)
            'sent' => Message::where('sender_id', $userId)
                             ->whereNull('deleted_at')
                             ->count(),
            // Papelera (Mensajes soft deleted que pertenecen al usuario)
            'trash' => Message::onlyTrashed()
                              ->where(function ($query) use ($userId) {
                                  $query->where('receiver_id', $userId)
                                        ->orWhere('sender_id', $userId);
                              })->count()
        ];
        
        // El conteo de no leídos es específico del inbox
        $counts['unread'] = Message::where('receiver_id', $userId)
                                   ->whereNull('read_at')
                                   ->whereNull('deleted_at')
                                   ->count();

        return $counts;
    }

    /**
     * Muestra la bandeja de entrada del usuario autenticado.
     */
    public function index()
    {
        // Trae los mensajes que yo recibo, cargando la relación 'sender'
        $messages = Message::where('receiver_id', Auth::id())
                           ->whereNull('deleted_at')
                           ->with('sender')
                           ->latest()
                           ->get();
        
        $counts = $this->getNavigationCounts();

        return view('messages.index', [
            'messages' => $messages,
            'view_title' => '✉️ Bandeja de Entrada',
            'counts' => $counts,
            'current_view' => 'inbox'
        ]);
    }

    /**
     * Muestra los mensajes enviados por el usuario autenticado.
     */
    public function sent()
    {
        // Trae los mensajes que yo envío, cargando la relación 'receiver'
        $messages = Message::where('sender_id', Auth::id())
                           ->whereNull('deleted_at')
                           ->with('receiver') // ⬅️ Correcto: Cargamos el destinatario
                           ->latest()
                           ->get();

        $counts = $this->getNavigationCounts();

        return view('messages.index', [
            'messages' => $messages,
            'view_title' => '📤 Mensajes Enviados',
            'counts' => $counts,
            'current_view' => 'sent'
        ]);
    }

    /**
     * Muestra la papelera de mensajes (mensajes eliminados).
     */
    public function trash()
    {
        // Trae mensajes eliminados donde soy el receptor O el remitente.
        $messages = Message::onlyTrashed()
                           ->where(function ($query) {
                               $query->where('receiver_id', Auth::id())
                                     ->orWhere('sender_id', Auth::id());
                           })
                           // Cargamos ambos remitente y receptor para mostrar quién envió/recibió
                           ->with('sender', 'receiver') 
                           ->latest()
                           ->get();

        $counts = $this->getNavigationCounts();

        return view('messages.index', [
            'messages' => $messages,
            'view_title' => '🗑️ Papelera (Auto-Borrado en 7 días)',
            'counts' => $counts,
            'current_view' => 'trash'
        ]);
    }
    
    /**
     * Maneja la eliminación (soft delete) y restauración de múltiples mensajes.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'integer|exists:messages,id',
            'action' => 'required|in:delete,restore',
        ]);

        $ids = $request->input('message_ids');
        $action = $request->input('action');
        $userId = Auth::id();
        $baseQuery = Message::query();
        
        if ($action === 'restore') {
            $baseQuery = Message::onlyTrashed();
        }

        // Filtramos solo los mensajes que pertenecen al usuario (seguridad)
        $messagesToAct = $baseQuery->whereIn('id', $ids)
            ->where(function ($query) use ($userId) {
                $query->where('receiver_id', $userId)
                      ->orWhere('sender_id', $userId);
            })->get();

        $count = $messagesToAct->count();
        $successMessage = '';
        
        // Redirección Segura
        $redirectUrl = url()->previous() ?? route('messages.index');

        if ($count === 0) {
             return redirect()->to($redirectUrl)->with('error', 'No se encontraron mensajes válidos para realizar la acción.');
        }

        if ($action === 'delete') {
            Message::whereIn('id', $messagesToAct->pluck('id'))->delete();
            $successMessage = "Se han movido $count mensajes a la papelera. Se eliminarán permanentemente en 7 días.";
        } elseif ($action === 'restore') {
            Message::onlyTrashed()->whereIn('id', $messagesToAct->pluck('id'))->restore();
            $successMessage = "Se han restaurado $count mensajes correctamente.";
        }
        
        return redirect()->to($redirectUrl)->with('success', $successMessage);
    }
    
    /**
     * Muestra el formulario para crear un nuevo mensaje (Solo VIP/Admin pueden enviar).
     */
    public function create()
    {
        $userRole = Auth::user()->role ?? 'normal'; 
        
        if (!in_array($userRole, ['vip', 'admin'])) {
            return redirect()->route('messages.index')->with('error', 'Solo usuarios VIP y Admin pueden enviar mensajes.');
        }

        $users = User::where('id', '!=', Auth::id())->get();
        
        return view('messages.create', compact('users'));
    }

    /**
     * Almacena un mensaje recién creado (Solo VIP/Admin pueden enviar).
     */
    public function store(Request $request)
    {
        $userRole = Auth::user()->role ?? 'normal';
        if (!in_array($userRole, ['vip', 'admin'])) {
            return redirect()->route('messages.index')->with('error', 'Acción no autorizada.');
        }

        $request->validate([
            'receiver_id' => 'required|exists:users,id|not_in:'.Auth::id(),
            'content' => 'required|string|max:1000',
        ], [
            'receiver_id.not_in' => 'No puedes enviarte un mensaje a ti mismo.',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return redirect()->route('messages.index')->with('success', '¡Mensaje enviado correctamente!');
    }

    /**
     * Marca un mensaje como leído.
     */
    public function markAsRead(Message $message)
    {
        if ($message->receiver_id !== Auth::id() || $message->trashed()) {
            return response()->json(['error' => 'No autorizado o mensaje eliminado'], 403);
        }

        if (is_null($message->read_at)) {
            $message->read_at = now();
            $message->save();
        }

        return redirect()->route('messages.index');
    }

    /**
     * Mueve un mensaje a la papelera (Soft Delete).
     */
    public function destroy(Message $message)
    {
        if ($message->receiver_id !== Auth::id() && $message->sender_id !== Auth::id()) {
            return redirect()->route('messages.index')->with('error', 'No autorizado para eliminar este mensaje.');
        }

        $message->delete(); // Soft Delete

        // ⬅️ Redirección explícita a la URL de origen
        $redirectUrl = url()->previous() ?? route('messages.index');
        
        return redirect()->to($redirectUrl)->with('success', 'Mensaje movido a la papelera. Se eliminará permanentemente en 7 días.');
    }

    /**
     * Restaura un mensaje de la papelera.
     */
    public function restore($id)
    {
        $message = Message::onlyTrashed()->findOrFail($id);

        if ($message->receiver_id !== Auth::id() && $message->sender_id !== Auth::id()) {
            return redirect()->route('messages.trash')->with('error', 'No autorizado para restaurar este mensaje.');
        }

        $message->restore();

        $redirectRoute = ($message->receiver_id === Auth::id()) ? 'messages.index' : 'messages.sent';

        return redirect()->route($redirectRoute)->with('success', 'Mensaje restaurado correctamente.');
    }
}

