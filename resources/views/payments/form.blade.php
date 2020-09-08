<div id="form_container">
  @include('gateways.' . $invoice->pgateway->route . '.form')
</div>
<div id="pgmodal" class="pgmodal vertical-center hidden">
  <div class="text-center">
    <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
  </div>
</div>
