<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('icon.svg') }}" type="image/x-icon">


        <title>{{ config('app.name', 'Laravel') }}</title>
        <style>
            body{
                background:url("../../bg.webp") no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
            .sender{
                align-items: right;
                justify-self: end;
                background-color: aquamarine;
                width:auto;
                max-width:40em;
                margin:2%;
                padding: 1%;
            }
            .recipient{
                background-color: aqua;
                justify-self: left;
                width:auto;
                max-width:40em;
                margin:2%;
                padding: 1%;
            }
            #center{
                justify-self: center;
                align-items: center;
                width:auto;
                margin-top:10em;
                max-width:40em;
            }
            #img_file {
                display: none;
            }
        </style>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen ">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
                @yield('scripts')
            </main>
        </div>
    </body>
</html>

