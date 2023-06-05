<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <livewire:styles />

        <!-- Scripts -->
        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{mix('js/app.js')}}" defer></script>
        <script src="{{ mix('js/alpinejs.js') }}" defer></script>
        @if( in_array(Route::currentRouteName(), ['product-create','product-edit']) )
            <script src="{{ asset('js/currency.js') }}" defer></script>
        @endif

        <script src="{{ asset('js/alert.js') }}" defer></script>

        <style>
            [x-cloak] {
                display: none;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="flex justify-center h-10 mb-4">
                        <x-alert />
                        @if ($errors->any())
                            <div class="text-red-600 text-sm">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
        <livewire:scripts />
    </body>
</html>
