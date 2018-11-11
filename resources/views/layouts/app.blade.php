<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{config('app.name', 'Laravel')}}</title>
    <link href="{{ elixir("css/app.css") }}" rel="stylesheet">
    @yield('style')
</head>

<body id="app-layout">
@include('layouts.partial.navigation')
<div class="container">
    @include('flash::message')
    @yield('content')
</div>
@include('layouts.partial.footer')

<script src="{{ elixir("js/app.js") }}"></script>
@yield('script')
</body>

</html>
