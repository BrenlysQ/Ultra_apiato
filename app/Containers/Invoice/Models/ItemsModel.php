<?php

namespace App\Containers\Invoice\Models;
use App\Ship\Parents\Models\Model;
use Mpociot\Firebase\SyncsWithFirebase;
class ItemsModel extends Model
{

  protected $table = "api_invoice_items";
  protected $fillable = [
    'fee','total_amount','total_base','total_tax','invoice','feepu'
  ];
  protected $appends = ['invoice_type']; // Attr nuevo para obtener solo el nombre del modelo del invoiceable
  public function invoiceable(){
      return $this->morphTo();
  }
  public function getInvoiceTypeAttribute($value){
    $ret = explode(chr(92),$this->attributes['invoiceable_type']); //Obteo Solo el nombre del modelo del invoiceable EJE: itinmodel
    //dd($this->attributes['invoiceable_type']);
    return $ret[count($ret) - 1];
  }
  public function invoice(){
      return $this->belongsTo('App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel','invoice','id');
  }

}
