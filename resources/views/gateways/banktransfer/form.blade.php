<div class="row transwindow">
<div class="col-md-12">
  <h3>Datos de transferencia{{-- <span class="pull-right btn btn-default transwbtn">Cuentas</span> --}}</h3>
  <form accept-charset="UTF-8" action="/" id="payment-formbankt" method="post">
    <div class='form-row'>
      <div class='col-xs-12 form-group required'>
        <label class='control-label'>Banco destino</label>
        <div class="dropdown uchip_select" id="bankselect">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="pull-left selected_opt" data-value="">Seleccione un banco</span>
            <span class="caret pull-right"></span>
            <input id="bankdata" name="bankid" type='hidden'
            data-rule-required="true"
            data-rule-hiddenf="true"
            data-msg-required="Seleccione un banco.">
          </button>
          <input id="invoiceid" name="invoiceid" value="{{$invoice->id}}" type='hidden'>
          <ul class="dropdown-menu">
            @foreach($banklist as $i => $bank)
              <li class="option" data-name="{{$bank->name}}" data-id="{{$bank->id}}">
                <a href="#"><img src="{{$bank->img_url}}" width="24px" />{{$bank->name}}</a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class='col-xs-5 form-group required'>
        <label class='control-label'>Fecha</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-calendar fa-fw"></i>
          </span>
          <input class='form-control' id="input_pd" size='4' type='text' data-rule-required="true" data-msg-required="Seleccione una fecha.">
          <input id="payment_date" value='' name="payment_date" type='hidden'>
        </div>
      </div>
      <div class='col-xs-7 form-group required'>
        <label class='control-label'>Tipo de transacción</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-credit-card-alt fa-fw"></i>
          </span>
          <select class="form-control" name="type" id="type">
            <option selected="" value="1">Transferencia</option>
            <option value="2">Deposito</option>
          </select>
        </div>
      </div>
      <div class='col-xs-12 form-group required'>
        <div>
          <label class='control-label'>Número de confirmación</label>
          <input class='form-control' data-rule-required="true"
          data-msg-required="Introduzca el numero de confirmacion"
          id="confirmation_number" name="confirmation_number" size='4' type='text'>
        </div>
      </div>
    </div>
  </form>
  <div class="buttons">
    <span class="btn btn-primary col-md-8 col-md-offset-2 col-lg-2 col-lg-offset-5" id="btn-mkpaybt"><i class="fa fa-refresh" aria-hidden="true"></i> Declarar pago</span>
  </div>
</div>
</div>
<!-- <div class=" row transwindow">
<div class="col-md-12">
  <h3>Números de cuenta{{-- <span class="pull-right btn btn-default transwbtn">Volver</span> --}}</h3>
  @include('gateways.banktransfer.tab')
</div>
</div> -->
