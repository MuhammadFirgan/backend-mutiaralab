<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    protected $guarded = ["id"];

    public function penyedia_sampling() {
        return $this->belongsTo(penyedia_sampling::class, 'sampling_id');
    }
    
    public function marketing() {
        return $this->belongsTo(marketing::class);
    }

    public function po_ttd_quotation() {
        return $this->belongsTo(po_ttd_quotation::class);
    }
}
