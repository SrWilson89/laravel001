<x-app-layout>

    {{-- 1. Cabecera (Título de la página) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Enviar Nuevo Mensaje
        </h2>
    </x-slot>

    {{-- 2. Contenido Principal --}}
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                
                {{-- Botón Volver --}}
                <div class="px-6 pt-6">
                    <a href="{{ route('messages.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>

                <div class="p-6 lg:p-8">
                    <form action="{{ route('messages.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Campo Receptor --}}
                        <div class="space-y-2">
                            <label for="receiver_id" class="block text-sm font-semibold text-gray-700">
                                Receptor
                            </label>
                            <select id="receiver_id" name="receiver_id" required 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 bg-white hover:border-gray-400">
                                <option value="">Selecciona un usuario</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role ?? 'normal' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('receiver_id')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        {{-- Campo Contenido --}}
                        <div class="space-y-2">
                            <label for="content" class="block text-sm font-semibold text-gray-700">
                                Contenido del Mensaje
                            </label>
                            <textarea id="content" name="content" rows="8" required 
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 resize-y hover:border-gray-400"
                                      placeholder="Escribe tu mensaje aquí...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Botón Enviar --}}
                        <div class="flex items-center justify-end pt-6 border-t-2 border-gray-100">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>