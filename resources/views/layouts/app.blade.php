<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ $title ?? ''}} - {{ setting('website_name') }}</title>
    <!-- Favicon -->
    <link href="{{ setting('website_favicon') }}" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="{{ $class ?? '' }}">
@auth()
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('layouts.navbars.sidebar')
@endauth

<div class="main-content">
    @include('layouts.navbars.navbar')

    @if(session('status'))
    <div class="container-fluid mt-3">
        <div id="flash-status-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="transition: opacity 0.8s ease;">
            <strong>✅ {{ session('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <script>
        setTimeout(function() {
            var alert = document.getElementById('flash-status-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(function() { alert.parentElement.remove(); }, 800);
            }
        }, 3000);
    </script>
    @endif

    @yield('content')
</div>





@stack('js')
{!! setting('custom_html') !!}


</body>
</html>