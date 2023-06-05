<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-900 sm:items-center py-4 sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-8 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1">
                        @if (Route::has('login'))
                        <div>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-6 ">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-6 ">Log in</a>
                            @endauth
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
