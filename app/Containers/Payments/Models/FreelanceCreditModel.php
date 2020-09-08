<?php

namespace App\Containers\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class FreelanceCreditModel extends Model
{
    protected $table = 'puf_credit';

    protected $fillable = [
    	'satellite_id',
    	'due_date',
    	'credit_type_id',
    	'total_amount',
    	'freelance_id',
    	'currency'
    ];

}
