<?php

namespace App\Containers\Payments\Models;

use App\Ship\Parents\Models\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class PupHistoryModel extends Model
{
  protected $table = "pup_history";
  protected $fillable = ['idinvoice'];
}
