<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title></title>
        {{-- <title>@yield('title', app_name())</title> --}}

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'La forma más sencilla de pagar tus viajes')">
        <meta name="author" content="@yield('meta_author', 'PlusUltraTechnology')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')
        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}{{-- 
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEh4Sl0sibVcOQVnN" crossorigin="anonymous"> --}}
{{--         {{ Html::style('css/frontend.css') }} --}}
        
        <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('css/payment.css') }}" />
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        {{-- @langRTL
            {{ Html::style('css/rtl.css') }}
        @endif --}}

        @yield('after-styles')
        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>        
    <body id="app-layout">
        <div id="app">
            {{-- @if ($logged_in_user)
                @include('includes.partials.logged-in-as')    
                @include('frontend.includes.nav')
            @endif --}}
            
            @yield('content')
            
            <!-- container -->
        </div><!--#app-->
        {{-- @include('frontend.includes.footertemplate') --}}
        
        <!-- Scripts -->
        @yield('before-scripts')
        {{-- {{ Html::script('js/frontend.js') }} --}}
        <script src="{{ URL::asset('js/app.js') }}"></script>
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
        <script src="{{ URL::asset('js/bootstrap-select.js') }}"></script>
        <script src="{{ URL::asset('js/uchip.js') }}"></script>
        <script src="{{ URL::asset('js/payment.js') }}"></script>
        @yield('after-scripts')

        {{-- @include('includes.partials.ga') --}}
    </body>

</html>