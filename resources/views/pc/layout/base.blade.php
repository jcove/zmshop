<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if(isset($title)) {{$title}}-{{config('shop.site_title', '')}} @else {{config('shop.site_title', '')}}@endif</title>
    <meta name="keywords" content="@yield('keywords', config('site.site_keywords'))"/>
    <meta name="description" content="@yield('description', config('site.site_description'))"/>
    <!-- Styles -->
    @include('pc.widgets.style')
    @yield('style')


</head>
<body>
<div id="app">
    @yield("body")
</div>
@include('pc.widgets.before_js_load')
<script src="{{mix('js/manifest.js')}}"></script>
<script src="{{mix('js/vendor.js')}}"></script>
<script src="{{mix('js/app.js')}}"></script>
@include('pc.widgets.script')

</body>
</html>