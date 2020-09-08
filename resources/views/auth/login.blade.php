

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Plus Ultra Pagos - La primera plataforma de pagos del sector turistico</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'La forma más sencilla de pagar tus viajes')">
        <meta name="author" content="@yield('meta_author', 'PlusUltraTechnology')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ URL::asset('css/login.css') }}" />
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
          <div class="container">

              <div class="row">

              <div class="title-login">
                <img src="{{ URL::asset('images/logo_pultrap.png') }}" class="text-center" style="width:90%">
              </div>

                <div class="col-sm-4">
                  <div class="panel panel-login panel-default account-form">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body active" id="div_form">
                      <p class="bg-success pwd-changed" style="display:none">El cambio de clave se ha realizado con exito.</p>
                      <form class="form-signin" id="form-signin">
                        <input type="text" id="email" name="email" class="form-control" placeholder="Email" required autofocus>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <span class="panel-line-or">or</span>
                        <div class="panel-login-sesion">
                          <a href="/auth/facebook"><img src="{{ URL::asset('images/facebook.png') }}"></a>
                          <a href="/auth/twitter"><img src="{{ URL::asset('images/twitter.png') }}"></a>
                          <a href="/auth/google"><img src="{{ URL::asset('images/google.png') }}"></a>
                        </div>
                        <span class="btn btn-primary btn-block btn-login" >
                          Ingresar</span>
                      </form>
                      <a href="#form_reset" class="form-handler">Recuperar contraseña</a>

                      <a href="#formreg" class="form-handler form-recover">No tienes cuenta?  <span>Crear Cuenta</span></a>
                    </div>
                    <div class="panel-body" id="formreg">
                      <form class="form-signin" id="new-account">
                        <input type="text" name="name" data-rule-required="true" data-msg-required="Nombre y apellidos" class="form-control" placeholder="Nombre y apellido">
                        <input type="text" name="email" class="form-control" placeholder="Email"
                        data-rule-required="true" data-rule-email="true" data-msg-required="Direccion de email invalida.">
                        <input type="password" class="form-control" id="passwordnew"
                        data-rule-required="true" data-rule-minlength="6" data-msg-minlength="Minimo 6 caracteres" data-msg-required="Introduzca una contrasena" name="password" placeholder="Contrasena">
                        <input type="password" class="form-control" data-rule-required="true" data-msg-required="Confirme la contrasena"
                        data-rule-equalTo="#passwordnew" data-msg-equalTo="Las contrasenas no coinciden" name="cpassword" placeholder="Confirmar contrasena">
                        <span class="btn btn-primary btn-block top-spacer" id="btnnewacc" >
                          Crear cuenta</span>
                        <a href="#div_form" class="form-handler btn btn-danger btn-block btn-cancelar" >
                          Cancelar</a>
                      </form>
                    </div>
                    <div class="panel-body" id="form_reset">
                      <p class="bg-danger address-incorrect" style="display:none">La direccion de correo no esta registrada.</p>
                      <form class="form-signin" id="form-reset">
                        <label class="label-login">Introduzca su direccion de email:</label>
                        <input type="text" id="email_reset" name="email_reset" class="form-control"
                        placeholder="Email" data-rule-email="true" data-rule-required="true"
                        data-msg-email="Direccion de email invalida"
                        data-msg-required="Introduzca su direccion de email">
                        <span class="btn btn-primary btn-block btn-reset-pass top-spacer" >
                          Recuperar cuenta</span>
                          <a href="#div_form" class="form-handler btn btn-danger btn-block btn-cancelar" >
                            Cancelar</a>
                      </form>
                    </div>
                    <div class="panel-body" id="form_sendcode">
                      <p class="bg-success sent-succes">Hemos enviado un codigo de 6 digitos a su cuenta de correo.</p>
                      <p class="bg-danger incorrect-reset" style="display:none">La direccion de correo no esta registrada.</p>
                      <form class="form-signin" id="form-sendcode">
                        <input type="text" id="reset_code" name="reset_code" class="form-control"
                        placeholder="Codigo enviado" data-rule-required="true"
                        data-msg-required="Codigo invalido">
                        <input type="hidden" name="token" id="token_reset" value="" >
                        <input type="password" class="form-control" id="password_reset"
                        data-rule-required="true" data-rule-minlength="6" data-msg-minlength="Minimo 6 caracteres" data-msg-required="Introduzca una contrasena" name="password" placeholder="Contrasena">
                        <input type="password" class="form-control" data-rule-required="true" data-msg-required="Confirme la contrasena"
                        data-rule-equalTo="#password_reset" data-msg-equalTo="Las contrasenas no coinciden" name="cpassword" placeholder="Confirmar contrasena">
                        <span id="btn-newpass" class="btn btn-primary btn-block top-spacer" >
                          Cambiar password</span>
                          <a href="#div_form" class="form-handler btn btn-danger btn-block btn-cancelar" >
                            Cancelar</a>
                      </form>
                    </div>
                  </div>
              </div>

              <div class="col-sm-8">
                <div class="panel panel-default panel-ultrapago">
                  <div class="panel-heading-ultrapago">¿Que es PlusUltra Pagos?</div>
                  <div class="panel-body pup-info">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Donec varius augue lacinia malesuada dictum. Integer pretium a ligula eget condimentum.
                    Sed tortor leo, consequat ac mi at, dictum vestibulum urna. Vivamus cursus lorem a justo
                    dictum, maximus condimentum ex convallis.
                    <div class="panel-image">
                      <img src="{{ URL::asset('images/logo_pultrap.png') }}" class="text-center">
                    </div>
                    <span class="pull-left"><a href="#" id="newaccbutton"><i class="fa fa-external-link-square"></i>Sitio Web</a></span>
                    <span class="pull-right"><a href="#">Terminos y condiciones</a></span>
                  </div>
                </div>
              </div>
             {{--  <div class="col-sm-2">
                <div class="panel panel-default">
                  <div class="panel-heading">Ingresar con</div>
                  <div class="panel-body">
                    <img src="https://www.facebook.com/images/fb_icon_325x325.png" style="width:50%">
                  </div>
                </div>
              </div> --}}

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
        <script src="{{ URL::asset('js/login.js') }}"></script>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </body>
</html>
