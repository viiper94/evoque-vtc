<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns:og="http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('meta')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139146641-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-139146641-2');
    </script>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}" media="screen,projection"/>
    @include('layout.scripts')
    @yield('assets')
    <title>@yield('title', trans('general.vtc_evoque'))</title>
</head>
<body>

    @include('layout.navbar')

    @yield('content')

    @include('layout.footer')


</body>
</html>
