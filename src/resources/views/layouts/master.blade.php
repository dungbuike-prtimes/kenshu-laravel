<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('/css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/styles.css') }}">
</head>
<body>
    @include('layouts.header')
<div class='container'>
    @yield('content')
</div>
</body>
    @stack('script')
</html>
