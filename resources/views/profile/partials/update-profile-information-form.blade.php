<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Actualiza la información de perfil y la dirección de correo electrónico de tu cuenta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- 1. AÑADIDO ENCTYPE PARA PERMITIR SUBIDA DE ARCHIVOS --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        
        {{-- 2. CAMPO: FOTO DE PERFIL --}}
        <div>
            <x-input-label for="profile_photo" :value="__('Foto de Perfil')" />

            {{-- Avatar actual o placeholder --}}
            <div class="mt-2 flex items-center gap-4">
                @if (Auth::user()->profile_photo_path)
                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-20 h-20 rounded-full object-cover shadow-lg border-2 border-indigo-100">
                @else
                    {{-- Placeholder simple si no hay foto --}}
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center shadow-lg border-2 border-gray-300">
                        <span class="text-gray-500 text-3xl">👤</span>
                    </div>
                @endif
                
                {{-- Campo de archivo --}}
                <input id="profile_photo" name="profile_photo" type="file" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100" />
            </div>
            
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        {{-- CAMPO: NOMBRE --}}
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- CAMPO: EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
