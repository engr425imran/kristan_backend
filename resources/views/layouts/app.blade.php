<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta property="og:image" content="{{asset('/images/favicon.png')}}" />
        <meta name="asset-url" content="{{asset('/')}}" />
        <title>{{ isset($title) ? $title  :  "ValetPro"}} </title>
        <meta name="description" content="{{ isset($description) ? $description :'ValetPro'}}">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="base-url" content="{{ url('/') }}" />
        <meta name="authenticate" content="{{Auth::user()}}" />
        <meta author="Mian Roshan" />
        <link rel="shortcut icon" type="image/png" href="{{asset('assets/img/favicon.png')}}"/>
        <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}"/>
        <meta property="og:image" itemprop="image" content="{{asset('assets/img/favicon.png')}}">
        <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/metismenu.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet" type="text/css" />
        @yield('styles')
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUNZZgE72aRG8H_aW_f9K1y1SedPg3LJI&libraries=places"></script>
        <script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/modernizr.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/js/common.js')}}"></script>
    </head>
    <body>
        <div id="wrapper">
                @include('includes.header')
            <div>      
                @yield('content')
            </div>
            @include('includes.footer')
        </div>
        @yield('scripts')
    </body>
</html>
<body>