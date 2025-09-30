<x-app-layout>

    {{-- 1. Cabecera (Título de la página) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✍️ Enviar Nuevo Mensaje
        </h2>
    </x-slot>

    {{-- 2. Contenido Principal --}}
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                
                {{-- Botón Volver --}}
                <div class="mb-6">
                    <a href="{{ route('messages.index') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200 transition">
                        ⬅️ Volver
                    </a>
                </div>

                <form action="{{ route('messages.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Campo Receptor --}}
                    <div>
                        <label for="receiver_id" class="block font-medium text-sm text-gray-700 mb-1">Receptor:</label>
                        <select id="receiver_id" name="receiver_id" required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            <option value="">Selecciona un usuario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role ?? 'normal' }})
                                </option>
                            @endforeach
                        </select>
                        @error('receiver_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Campo Contenido --}}
                    <div>
                        <label for="content" class="block font-medium text-sm text-gray-700 mb-1">Contenido del Mensaje:</label>
                        <textarea id="content" name="content" rows="6" required 
                                  class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            🚀 Enviar Mensaje
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>