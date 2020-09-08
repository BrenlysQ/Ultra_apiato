<p>
  Hola {{$payment->customer->name}} un gusto en saludarle, que hemos resgitrado una transferencia bancaria a su
  a su nombre, por un total de <b>{{$payment->amount}} {{$payment->currency->code}}</b>, este pago sera adjudicado a
  nuestro aliado comercial <b>{{$payment->satellite->name}}</b>,
  correspondiente a su compra número <b>{{$payment->idinvoice}}</b>. Nuestro departamento de administración
  procederá con la verificación del mismo en un lapso no mayor a 48 hrs.
  <br><br><br><br><br>
  <b>A continuación los detalles de su pago:</b><br><br>
 <b>Fecha de pago:</b> {{$payment->payment_date}}<br>
 <b>Banco destino:</b> {{$payment->pgatewaydata->bank->name}}<br>
 <b>Numero de confirmacion:</b> {{$payment->pgatewaydata->confirmation}}<br>
 <b>Monto:</b> {{$payment->amount}}<br>
</p>
<p>
  Una vez que uno de nuestros asesores de administracion verifique su pago, le contactaremos con el resultado
  de la verificacion, además tambbien le informaremos a nuestro aliado.
</p>
