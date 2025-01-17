<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Scochal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <style>
            body{
                background:url("../../landing-bg.jpg") no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
            nav{
                text-align: end;
                justify-items: end;
            }
            nav a{
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                color:white;
                background-color: greenyellow;
                font-size: 150%;
                padding:1%;
                margin:1%;
                border-radius: 20%;
            }
            .left{

            }

        </style>

    </head>
    <body>
        <header>
        @if (Route::has('login'))
            <nav>
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                    >
                        Dashboard
                    </a>

                    @else
                    <a
                        href="{{ route('login') }}"
                    >
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                        >
                            Register
                        </a>
                    @endif
                @endauth
            </nav>

        @endif
    </body>
</html>
