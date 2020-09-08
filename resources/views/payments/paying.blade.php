@extends('layouts.app')

@section('content')
  <div class='userscreen bg-primary'>
                <h5><img src="http://www.newsshare.in/wp-content/uploads/2017/04/Miniclip-8-Ball-Pool-Avatar-11.png" class="avatar">
                  Usted ha iniciado sesion como <b>{{Auth::user()->name}}</b>
                  <small class="pull-right logoff"><a href="/logout">
                    <i class="fa fa-sign-out circlei" aria-hidden="true"></i>
                    Salir</a></small>
                </h5>
              </div>

          <div class="container mainwindowpayment">
            <!-- <div class="row wizard-follando">
              <ul class="wizard">
                <li><h3 >1</h3>Inicie Sesion<i class="fa fa-credit-card-alt"></i></li>
                <li><h3 >2</h3>Seleccione Metodo de Pago<i class="fa fa-lock"></i></li>
                <li><h3 >3</h3>Realiza el pago<i class="fa fa-desktop"></i></li>
                <li style="margin-right: 10px;"><h3 >4</h3>Resultado de transacion<i class="fa fa-thumbs-up"></i></li>
              </ul>
            </div> -->
            <div class='row roweqheight'>

                @php ($debt = ($invoice->total_amount - $invoice->total_paid))
                <div class='col-sm-3'>
                  <div class="paymentwindow">
                    <h4>Información de la compra</h4>
                    <div class="paymentinfo">
                      <h5 class="invoice-text-title">Comercio:
                        <span class="invoice-text">{{$invoice->satellite->name}}</span>
                      </h5>
                      <hr>
                      <h5 class="invoice-text-title">Código de compra:
                        <span class="invoice-text">{{$invoice->id}}</span>
                      </h5>
                      <hr>
                      <h5 class="invoice-price">Pendiente por pagar:
                        <span class="invoice-text">{{$debt}} {{$invoice->currency_data->code}}</span>
                      </h5>
                      <h5 class="">Total:
                        <span class="invoice-mainprice">{{$invoice->total_amount}} {{$invoice->currency_data->code}}</span>
                      </h5>
                      <hr>
                      <label class="radio-inline">
                        <input type="radio" checked="" class="radiopp" name="optradio">Pago total
                      </label>
                      <label class="radio-inline">
                        <input type="radio" class="radiopp" name="optradio">Pago parcial
                      </label>
                      <input type="hidden" id="invid" value="{{$invoice->id}}">
                      <div class="ppaymentdiv" style="display:none">
                        <div class='form-row'>
                          <div class='col-xs-12 form-group cardnumber required'>
                            <label class='control-label'>Total a pagar</label>
                            <input autocomplete='off' class='form-control' id="totpay" value="{{$debt}}" placeholder="{{$debt}}" type='text'>
                          </div>
                        </div>
                      </div>{{--
                      <div class="row buttons">
                        <span class="btn btn-warning col-md-8 col-md-offset-2" id="btnchpgw">
                          <i class="fa fa-refresh" aria-hidden="true"></i> Cambiar forma de pago
                        </span>
                      </div> --}}
                    </div>
                  </div>
                  <div class="paymentwindow" style="display:none">
                      @include('payments.pgateways')
                  </div>
                </div>
                <div class='col-sm-9 nopadding'>
                  <div class="card pay-card">
                    {{-- <h3>Forma de Pago</h3> --}}
                    <div id="paymenttab" class="" style="font-size: 14px;text-align: left;">
                    <!-- <ul class="nav nav-payment-tabs" style="padding: 20px;">
                      <!-- <li class="active" >
                        <a  href="#tab-1" data-toggle="tab">
                          <div class="wrapper-icon">
                            <i class="fa fa-credit-card fa-3" aria-hidden="true"></i>
                          </div>Tarjeta de credito
                        </a>
                      </li>
                      <li>
                        <a  href="#tab-2" data-toggle="tab">
                          <div class="wrapper-icon">
                            <i class="fa fa-credit-card fa-3" aria-hidden="true"></i>
                          </div>Transferencia
                        </a>
                      </li>
                    </ul> -->
                    <div class="tab-content tab-payment-content">
                      <!-- <div class="tab-pane active" id="tab-1">
                        @include('gateways.creditcard.form')
                      </div> -->
                      <div class="tab-pane active" id="tab-2">
                        @include('gateways.banktransfer.form')
                      </div>
                        {{-- </div> --}}
                    </div>
                    </div>
                  </div>
                  {{-- @include('payments.form') --}}

                </div>
            </div>
            <div id="paymodal" class="pgmodal vertical-center hidden">
              <div class="text-center">
                <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
              </div>
            </div>
          </div>
          <div class="text-center">
            <span style="font-size: 10px;">Powered by: </span>
            <img src="{{ URL::asset('images/logo_pultrap.png') }}" class="text-center" style="width:15%;margin:10px 0px 15px">
          </div>

@endsection
