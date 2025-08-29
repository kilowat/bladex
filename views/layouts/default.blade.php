@useCss([
    'assets/css/bootstrap.min.css',
    'assets/css/font-awesome.min.css',
    'assets/css/elegant-icons.css',
    'assets/css/jquery-ui.min.css',
    'assets/css/magnific-popup.css',
    'assets/css/owl.carousel.min.css',
    'assets/css/slicknav.min.css',
    'assets/css/style.css',
])
@useJs([
    'assets/js/jquery-3.3.1.min.js',
    'assets/js/bootstrap.min.js',
    'assets/js/jquery.magnific-popup.min.js',
    'assets/js/jquery-ui.min.js',
    'assets/js/mixitup.min.js',
    'assets/js/jquery.countdown.min.js',
    'assets/js/jquery.slicknav.js',
    'assets/js/owl.carousel.min.js',
    'assets/js/jquery.nicescroll.min.js',
    'assets/js/main.js',
])

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ashion Template">
    <meta name="keywords" content="Ashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ashion | Template</title>
    @styles
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- Offcanvas Menu Begin -->
    @include('ui.offcanvas-menu')
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('ui.header')
    <!-- Header Section End -->

    @yield('content');

    <!-- Footer Section Begin -->
    @include('ui.footer')
    <!-- Footer Section End -->

    <!-- Search Begin -->
    @include('ui.search-model')
    <!-- Search End -->

    <!-- Js Plugins -->
    @scripts
</body>

</html>