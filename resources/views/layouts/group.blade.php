<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"><!-- CSRF Token -->
    @include('partial.meta.group')
    <title>Topnews @yield('title')</title>
    @include('partial.head')
</head>
<body>
    <div class="container group-container">
        <div class="row">
            <div class="col-sm-2 no-padding">@include('partial.sidenav')</div>
            <div class="col-sm-10 no-padding">
                <div class="col-sm-12">@include('partial.headerbar')</div>
                <div class="col-sm-12">@include('block.main-slider')</div>
                <div class="col-sm-12">
                    <div class="row articles-and-sidebar">
                        <!-- section.content -->
                        <div class="col-sm-8 no-margin">@yield('content')</div>
                        <div class="col-sm-4 no-margin">@include('partial.sidebar')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partial.footer')
    @include('partial.scripts')
    <!-- section.scripts -->
    @yield('scripts')
</body>
</html>