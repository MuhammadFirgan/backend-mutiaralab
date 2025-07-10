<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penyedia_sampling extends Model
{

    protected $guarded = ["id"];

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function marketing() {
        return $this->belongsTo(Marketing::class);
    }

    public function koor_teknis() {
        return $this->belongsTo(Koor_Teknis::class);
    }

    public function quotation() {
        return $this->hasOne(Quotation::class);
    }

}
