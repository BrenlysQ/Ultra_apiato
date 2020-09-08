<p>
  Hola {{$payment->customer->name}} un gusto en saludarle, lamentamos informarle que nuestro departamento de administración no ha podido verificar una transferencia bancaria a su
  a su nombre, por un total de <b>{{$payment->amount}} {{$payment->currency->code}}</b>, este pago sería adjudicado a
  nuestro aliado comercial <b>{{$payment->satellite->name}}</b>,
  correspondiente a su compra número <b>{{$payment->idinvoice}}</b>. Uno de nuestros asesores le contactara en breve para canalizar el problema.
  <br><br><br><br><br>
  <b>A continuación los detalles de su pago:</b><br><br>
 <b>Fecha de pago:</b> {{$payment->payment_date}}<br>
 <b>Banco destino:</b> {{$payment->pgatewaydata->bank->name}}<br>
 <b>Numero de confirmacion:</b> {{$payment->pgatewaydata->confirmation}}<br>
 <b>Monto:</b> {{$payment->amount}} {{$payment->currency->code}}<br>
 <b>Procesado por:</b> {{$payment->processor->name}}<br>
 <br><br><br><br><br>
 <b>Detalles de su compra:</b><br><br>
  <b>Peniente por pagar:</b> {{($payment->invoice->total_amount - $payment->invoice->total_paid)}} {{$payment->currency->code}}<br>
  <b>Total pagado:</b> {{$payment->invoice->total_paid}} {{$payment->currency->code}}<br>
  <b>Total orden:</b> {{$payment->invoice->total_amount}} {{$payment->currency->code}}<br>
</p>
