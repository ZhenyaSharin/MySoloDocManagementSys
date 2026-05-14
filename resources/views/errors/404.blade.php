<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Страница не найдена</title>

    <!-- Scripts -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="mt-4 py-4">
            <div class="container d-flex flex-column justify-content-center align-items-center">
				<h2>
					Объект не найден
				</h2>
				<h4>
					404 error
				</h4>
				<br>
				<a href="{{ url('/') }}" class="btn btn-secondary font-bold no-round px-4 py-3 font-up font-light box-shad" target="_top">
					<i class="fas fa-home fa-lg"></i>&nbsp;&nbsp;На главную страницу
				</a>
			</div>
        </main>
    </div>
</body>
</html>