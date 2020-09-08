<h3>Historial de pagos</h3>
@foreach($payments as $payment)
  <div>
    @if($payment->st == 1)
      <i class="fa fa-question" aria-hidden="true"></i>
    @elseif($payment->st == 2)
      <i class="fa fa-check" aria-hidden="true"></i>
    @else
      <i class="fa fa-ban" aria-hidden="true"></i>
    @endif

    {{$payment->pgateway->name}} {{$payment->payment_date}}
    <span class="pull-right"><b>{{$payment->amount}} {{$payment->currency->code}}</b></span>
  </div>
@endforeach
