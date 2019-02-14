<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-grey-lightest">
    <div id="app">
        <div class="w-full flex bg-blue-darkest shadow-lg p-6">
            <a href="/" class="font-semibold text-xl text-white no-underline">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>
        <main class="py-12">
            @yield('content')
        </main>
    </div>
</body>
</html>
