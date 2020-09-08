<ul class="nav nav-pills nav-stacked pgateways">
  @foreach($pgateways as $pgateway)
    @if($pgateway->id == $invoice->pgateway->id)
      <li id="pgateway-{{$pgateway->id}}" class="pgateway active" data-id="{{$pgateway->id}}"><a href="#">{{$pgateway->name}}</a></li>
    @else
      <li id="pgateway-{{$pgateway->id}}" class="pgateway" data-id="{{$pgateway->id}}"><a href="#">{{$pgateway->name}}</a></li>
    @endif
  @endforeach
</ul>
<input type="hidden" name="selected_pgateway" id="selected_pgateway" value="{{$invoice->pgateway->id}}">
<div class="bottom-align-text">
  <span class="btn btn-default btn-pgateway" id="chpgw">Cambiar</span>
  <span class="btn btn-danger btn-pgateway" id="cancelchpgw">Cancelar</span>
</div>
