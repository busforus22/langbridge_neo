<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="/fontawesome/css/all.min.css">

    <!-- Scripts -->
    @vite([
    'resources/sass/app.scss',
    'resources/js/app.js',
    'resources/css/style.css',
    'resources/js/translate.js',
    'resources/js/report.js',
    'resources/js/vocabulary-modal.js',
    'resources/js/quiz.js',
    'resources/css/home.css',
    'resources/js/timezone.js',
    'resources/css/app.css',
    'resources/css/profile.css'
    ])
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="lang-blue">
    <div id="app" class="d-flex">
        <x-navbar />
        <div class="main-content flex-grow-1 rounded-3 d-flex">
            @yield('sidebar')
            <main class="py-4 flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>
</body>
@yield('scripts')

</html>