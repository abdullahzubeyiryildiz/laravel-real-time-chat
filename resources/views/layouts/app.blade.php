<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chat') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"  />
    @yield('customcss')
</head>
<body>
    <div class="wrapper">
        @yield('content')
    </div>


    <script src="{{asset('js/jquery.min.js')}}"></script>




<script>

    function  playAudi() {
       /* const media = document.getElementById('alarmNotification');
        media.play();*/


        var myAudio = new Audio("{{asset('ses/sound.mp3')}}");
        myAudio.play();
      }
      window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
    </script>


    <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>
    <script src="{{ asset('/js/laravel-echo-setup.js') }}"></script>

    <script src="{{ asset('/js/chat.js') }}"></script>

    @yield('customjs')


</body>
</html>
