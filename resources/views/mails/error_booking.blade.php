<p>
    Ha ocurrido un error reservando un vuelo.<br>
    <br>
    Detalles del itinerario:<br>
    Desde: {{$data->departure_city}} en {{$data->departure_date}}<br>
    Hasta: {{$data->destination_city}} en  {{$data->return_date}}<br>
    Motor de busqueda:  @if($data->se == 1) Sabre @elseif($data->se == 2) Kiu @else Sabre @endif <br>
    Adultos: {{$data->adult_count}} <br>
    Niños: {{$data->child_count}} <br>
    Infantes {{$data->inf_count}} <br>
    Detalles del error se encuentran en el adjunto.
</p>