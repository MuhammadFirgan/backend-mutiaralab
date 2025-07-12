<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    protected $guarded = ["id"];

    public function penyedia_sampling() {
        return $this->belongsTo(Penyedia_Sampling::class, 'sampling_id');
    }
    
    public function marketing() {
        return $this->belongsTo(Marketing::class);
    }

    public function po_ttd_quotation() {
        return $this->belongsTo(Po_Ttd_Quotation::class);
    }
}
