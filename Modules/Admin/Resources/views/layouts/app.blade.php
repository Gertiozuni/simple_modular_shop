<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lara Shop Admin</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Laravel Mix - CSS File --}}
     <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
</head>
<body>
    <div id="admin">
        @include('admin::layouts.header.nav')

        <div class="container my-3">
            @yield('content')
        </div>
    </div>
    {{-- Laravel Mix - JS File --}}
     <script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
