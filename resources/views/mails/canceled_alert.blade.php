<p>
  La factura numero <strong>{{$data->item->invoice->id}}</strong> posee una devolucion debido a que
  el itinerario generado en ella ha sido cancelado por el sistema de KIU y posee un saldo a favor del cliente.
  <br>
  El localizador del vuelo es <strong>{{$data->itinerary_id}}</strong>. Le pedimos por favor ponerse
  en contacto con el cliente lo antes posible para solventar la situacion.
  <br>
  Los datos del cliente son:<br>
  Nombre: <strong>{{$data->item->invoice->contact_pax->name}}&nbsp;{{$data->item->invoice->contact_pax->last_name}}</strong>
  Email: <strong>{{$data->item->invoice->contact_pax->email}}</strong><br>
  Telefono: <strong>{{$data->item->invoice->contact_pax->phone}}</strong>
</p>
