<p>
  <h3>Se ha generado un nuevo localizador para un itinerario:<h3>
  <h4>Detalles:<h4>
  <b>Fecha de la solicitud:</b> {{date('d/m/Y H:i:s')}}<br>
  <b>Localizador:</b> {{$data->itineraryid}}<br>
  <b>Itinerario:</b> {{$data->itinerary}}<br>
  <b>Cliente:</b> Cecilio Morales<br>
  <br>
  <h4>Pasajeros:<h4>
    @foreach($data->datapaxes as $pax)
      <b>Nombres:</b> {{$pax->firstname}}<br>
      <b>Apellidos:</b> {{$pax->lastname}}<br>
      <b>Tipo:</b> {{$pax->type}}<br>
      ----------------------------------------------------------<br>
    @endforeach

</p>
