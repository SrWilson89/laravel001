@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Perfil</h1>

        <a href="{{ route('notes.index') }}" class="btn btn-secondary">Volver al menu</a>

        {{-- Formulario para actualizar el nombre y el correo --}}
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    
        <hr style="margin-top: 40px; margin-bottom: 40px;">

        <h2>Cambiar Contraseña</h2>
        {{-- Formulario para actualizar la contraseña --}}
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Contraseña Actual:</label>
                <input type="password" id="current_password" name="current_password" required>
                @error('current_password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña:</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Nueva Contraseña:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
        </form>
    </div>
@endsection