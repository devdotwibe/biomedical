<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}" />

    <link href="{{ asset('fonts/font.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cssmenu.css') }}">

    <link href="{{ asset('css/slick.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/productlisting.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/lightbox.min.css') }}">


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description')" />
    <meta name="keywords" content="@yield('meta_keywords')" />

    <title><?php echo setting('SITE_NAME'); ?> | @yield('title')</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11160970668"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'AW-11160970668');
    </script>

</head>

<body>

    <header class="main-header">
        @include('layouts/header')
    </header>


    @yield('content')

    <footer class="main-footer">
        @include('layouts/footer')
    </footer>




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/jquery-validate.js') }}"></script>
    <script src="{{ asset('js/jquery-validate-additional-methods.js') }}"></script>

    <script src="{{ asset('js/slick.min.js') }}"></script>


    <script src="{{ asset('js/lightbox.min.js') }}"></script>
    <!-- <script src="{{ asset('js/jquery.easing.min.js') }}"></script> -->
    <script src="{{ asset('js/slick.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup2.min.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    @yield ('scripts')



</body>

</html>
