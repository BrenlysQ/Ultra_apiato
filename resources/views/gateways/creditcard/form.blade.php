<div class="row">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <form id="payment-formtdc" method="post">
        <div class='col-xs-12 nopadding form-group cardnumber required'>
          <label class='control-label'>Número de tarjeta</label>
          {{-- <input autocomplete='off'
          data-rule-required="true"
          data-rule-creditcard="true"
          name="card_number"
          data-msg-required="El numero de la tarjeta es invalido"
          data-msg-creditcard="El numero de la tarjeta es invalido"
           class='form-control card-number' size='20' type='text'> --}}

          <input placeholder="**** **** **** ****" type="text"
          autocomplete='off'
          data-rule-required="true"
          data-rule-creditcard="true"
          name="card_number" id="card_number"
          data-msg-required="El numero de la tarjeta es invalido"
          data-msg-creditcard="El numero de la tarjeta es invalido" class="form-control">
        </div>
        <div class='col-xs-8 form-group expiration required ' style="padding-left: 0;">
          <label class='control-label' style="display:block;">Fecha de Vencimiento</label>
          {{--
          <select class='form-control card-expiry-month'
          data-rule-required="true"
          data-msg-required="Mes"
          name="exp_month">
            @for($i = 1; $i <13; $i++)
              <option>{{($i<10) ? "0".$i : $i}}</option>
            @endfor
          </select> --}}
          <input placeholder='Mes / Año' type="text" name="expiry_date" id="expiry_date"
          data-rule-required="true"
          data-msg-required="Introuzca fecha de vencimiento"
          class="form-control"/>
          {{-- <select class='form-control card-expiry-month'
          data-rule-required="true"
          data-msg-required="Mes"
          name="exp_year">
            @for($i = 2017; $i <2024; $i++)
              <option>{{$i}}</option>
            @endfor
          </select> --}}
        </div>
        <div class='col-xs-4 nopadding form-group cvc required'>
          <label class='control-label'>CVC</label>
          {{-- <input autocomplete='off'
          data-rule-required="true"
          name="cvc_number"
          data-msg-required="CVC"
          class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'> --}}
          <input type="text"
          data-rule-required="true"
          name="cvc_number" id="cvc_number"
          data-msg-required="El CVC es requerido"
          class="form-control"/>
        </div>
        <div class='col-xs-6 nopadding form-group required'>
          <label class='control-label'>Cédula</label>
          <input class='form-control' name="doc_id"
          data-rule-required="true"
          data-msg-required="Número de cédula"
           size='4' type='text'>
        </div>
        <div class='col-xs-12 nopadding form-group required'>
          <label class='control-label'>Nombre del Tarjetahabiente</label>
          {{-- <input class='form-control' name="name_holder"
          data-rule-required="true"
          data-msg-required="Nombre como aparece en la tarjeta"
           size='4' type='text'> --}}
          <input placeholder="Nombre Apellido" type="text"
          name="name_holder" id="name_holder"
          data-rule-required="true"
          data-msg-required="Nombre como aparece en la tarjeta"
          class="form-control"/>
        </div>

        <span class="btn btn-primary col-xs-8 col-xs-offset-2 col-lg-4" id="btn-mkpaytdc"><i class="fa fa-refresh" aria-hidden="true"></i> Realizar pago</span>
    </form>
  </div>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class='card-wrapper'></div>
    <div class="row">
      <div class="col-xs-12 col-sm-11 col-md-9 container-rights">
        <h6>PROCESADO POR:</h6>
        <img src="{{ URL::asset('images/logo_instapago.png') }}" class="img-ccgateway text-center">
      </div>
    </div>
  </div>
</div>
{{-- <form>
    <input type="text" name="number">
    <input type="text" name="name"/>
    <input type="text" name="expiry"/>
    <input type="text" name="cvc"/>
</form> --}}
