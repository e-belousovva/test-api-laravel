<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo/>
        </x-slot>

        <x-validation-errors class="mb-4"/>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label value="{{ __('Email') }}"/>
                <x-input class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus/>
            </div>

            <div class="mt-4">
                <x-label value="{{ __('Password') }}"/>
                <x-input class="block mt-1 w-full" type="password" name="password" required
                         autocomplete="current-password"/>
            </div>


            <div class="flex items-center justify-end mt-4">


                <x-button class="ml-4">
                    {{ __('Login') }}
                </x-button>

                <a href="{{ url('auth/google') }}"
                   style="padding: 8px;border-radius:3px;">
                    <img src="{{url('/icons/google-search-icon-google-product-illustration-free-png.webp')}}" alt="ImageGoogle" width="200px"/>
                </a>
                <a href="{{ 'https://oauth.yandex.ru/authorize?client_id='.config('services.yandex.client_id').'&redirect_uri='.config('services.yandex.redirect').'&response_type=code&state=123' }}"
                   style="padding: 8px;border-radius:3px;">
                    <img src="{{url('/icons/Yandex_icon.svg.png')}}" alt="ImageYandex" width="200px"/>
                </a>
                <a href="{{ 'https://github.com/login/oauth/authorize?client_id='.config('services.github.client_id').'&redirect_uri='.config('services.github.redirect').'&scope=user&response_type=code&state=' }}"
                   style="padding: 8px;border-radius:3px;">
                    <img src="{{url('/icons/github.png')}}" alt="ImageGithub" width="200px"/>
                </a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
