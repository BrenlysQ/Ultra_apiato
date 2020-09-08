<?php

namespace App\Containers\Payments\Models;

use App\Ship\Parents\Models\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class PupStatusModel extends Model
{
  protected $table = "pup_status";
  protected $fillable = ['name'];
}
