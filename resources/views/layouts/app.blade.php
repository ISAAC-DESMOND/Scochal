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
	    .notification-box.success {
       		 background-color: #4CAF50;
    	    }


	    .notification-box.info {
	        background-color: #2196F3;
	    }

        </style>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
	<script>
    	window.userID ={{ Auth::id() }};
	</script>

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
	    @if(session('notification'))
    		<x-notification 
        		:type="session('notification')['type']" 
        		:message="session('notification')['message']" 
    		/>
	    @endif

	<div x-data="rtnotifications()" x-init="init()"  class="fixed top-5 left-1/2 transform -translate-x-1/2 space-y-2 z-50 w-full max-w-sm">
	  <template x-for="note in notifications" :key="note.id">
	     <div  x-data="{visible: false}"
	    	x-init="setTimeout(() => show = false, 5000)"
		x-transition:enter="transition ease-out duration-300"
    		x-transition:enter-start="opacity-0 -translate-y-2"
    		x-transition:enter-end="opacity-100 translate-y-0"
    		x-transition:leave="transition ease-in duration-300"
    		x-transition:leave-start="opacity-100 translate-y-0"
    		x-transition:leave-end="opacity-0 -translate-y-2"
    		class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 px-6 py-4 border rounded shadow-md w-full max-w-md notification-box"
		:class="note.type"
    		role="alert"
		>
    		<div class="flex items-center space-x-3">
	      		<span x-text="note.message"></span>
	    	</div>
	   </template>
	</div>

            <!-- Page Content -->
            <main class="w-full">
                {{ $slot }}

     @yield('scripts')
            </main>
        </div>
    </body>≈
</html>

