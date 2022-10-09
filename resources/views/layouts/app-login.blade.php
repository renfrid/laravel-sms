<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>
    <title>ESRF | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="ESRF SMS Platform" name="description" />
    <meta content="Renfrid Ngolongolo" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ URL::asset('assets/images/favicon.png') }}">

    <!-- Layout config Js -->
    {{-- <script src="{{ asset('assets/js/layout.js') }}"></script> --}}
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ URL::asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <main role="main" class="mt-0">
        @yield('content')
    </main>

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/plugins.js') }}"></script> --}}

    <!-- particles js -->
    <script src="{{ URL::asset('assets/libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ URL::asset('assets/js/pages/particles.app.js') }}"></script>
    <!-- password-addon init -->
    <script src="{{ URL::asset('assets/js/pages/password-addon.init.js') }}"></script>
    <!-- notify js -->
    <script src="{{ URL::asset('assets/js/notify.js') }}"></script>
    <!-- validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>

    @yield('script')
</body>
</html>
