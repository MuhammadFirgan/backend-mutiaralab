<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    protected $guarded = ["id"];
    public function sampling() {
        return $this->belongsTo(penyedia_sampling::class);
    }
    
    public function marketing() {
        return $this->belongsTo(marketing::class);
    }
}
