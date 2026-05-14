<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/icon.PNG" type="image/png">
    <title>@yield('title', config('app.name')) - @yield('page')</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<!--     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{url('assets/css/bootstrap.min.css')}}"/>  -->

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
    @include('layouts.header')
    <div id="app">
    @yield('content')
    </div>
    @include('layouts.footer')
<!--       <a href="#top" class="button-up">
        <i class="fa fa-arrow-circle-up fa-2x"></i>
      </a> -->

    <!-- Scripts -->
    <script src="/js/app.js" defer></script>
    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/42d566f993.js" crossorigin="anonymous"></script>
    <script src="/js/jquery.malihu.PageScroll2id.min.js" defer></script>
    <script src="/js/script.js" defer></script>
</body>
</html>