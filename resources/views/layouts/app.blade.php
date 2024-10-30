<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::user()->api_token ?? '' }}">

    <title>Tecnipes</title>
    <link rel="icon" href="{{ asset('/images/logo-expandido.ico') }}">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1CmrxMRARb6XQD6tJHRdab1DTR0lJ3bErJbiIzTIN3eDn6uv/D9vn7ULYUeugitP" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/photoswipe/dist/photoswipe.css">
    <link rel="stylesheet" href="https://unpkg.com/photoswipe/dist/photoswipe-ui-default.css">


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>

<body>
    @auth @include('layouts.navbar') @endauth
    <div id="app">
        <main class="pt-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+Y+ji4c5c5f41m0H6zu4Hf0xmC++PpjbR4" crossorigin="anonymous">
    </script>



</body>

</html>
