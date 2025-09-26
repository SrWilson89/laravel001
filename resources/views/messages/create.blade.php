@extends('layouts.app')

@section('content')
<div class="container">
    <div class="button-container">
        <a href="{{ route('messages.index') }}" class="btn btn-secondary">
            ⬅️ Volver
        </a>
        <h1>✍️ Enviar Nuevo Mensaje</h1>
        <span></span>
    </div>
    
    <form action="{{ route('messages.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="receiver_id">Receptor:</label>
            <select id="receiver_id" name="receiver_id" required>
                <option value="">Selecciona un usuario</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role ?? 'normal' }})</option>
                @endforeach
            </select>
            @error('receiver_id')
                <small class="text-danger" style="color: red;">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="content">Contenido del Mensaje:</label>
            <textarea id="content" name="content" rows="6" required></textarea>
            @error('content')
                <small class="text-danger" style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            🚀 Enviar Mensaje
        </button>
    </form>
</div>
@endsection