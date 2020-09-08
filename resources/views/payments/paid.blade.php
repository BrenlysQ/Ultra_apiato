<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title></title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Laravel 5 Boilerplate')">
        <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ URL::asset('css/payment.css') }}" />
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

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
          <div class="container mainwindowpayment">
              <div class='row userscreen bg-primary'>
                <h5><img src="{{Auth::user()->social_avatar}}" class="avatar">
                  Usted ha iniciado sesion como <b>{{Auth::user()->name}}</b>
                  <small class="pull-right logoff"><a href="/logout">
                    <i class="fa fa-sign-out circlei" aria-hidden="true"></i>
                    Salir</a></small>
                </h5>
              </div>
              <div class='row roweqheight'>
                  <div class='col-md-6 card'>
                    @include('payments.history')
                  </div>
                  @php ($debt = ($invoice->total_amount - $invoice->total_paid))
                  <div class='col-md-6 card'>
                    <div class="paymentwindow">
                      <h3>Información de la compra</h3>
                      <div class="paymentinfo">
                        <h4>Comercio: <span>{{$invoice->satellite->name}}</span></h4>
                        <h4>Código de compra: <span>{{$invoice->id}}</span></h4>
                        <h4 class="text-right">Pendiente por pagar: <span>{{$debt}} {{$invoice->currency_data->code}}</span></h4>
                        <h4 class="text-right">Total: <span>{{$invoice->total_amount}} {{$invoice->currency_data->code}}</span></h4>
                        <div class="alert alert-success" role="alert">
                          Esta factura ha sido cancelada en su totalidad!
                        </div>

                      </div>
                    </div>
                  </div>
              </div>
              <div id="paymodal" class="pgmodal vertical-center hidden">
                <div class="text-center">
                  <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                </div>
              </div>
          </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
        <script src="{{ URL::asset('js/bootstrap-select.js') }}"></script>
        <script src="{{ URL::asset('js/uchip.js') }}"></script>
        <script src="{{ URL::asset('js/payment.js') }}"></script>
    </body>
</html>
