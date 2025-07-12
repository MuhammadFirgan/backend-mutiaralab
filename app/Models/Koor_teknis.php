<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class koor_teknis extends Model
{

    protected $guarded = ["id"];

    public function marketing() {
        return $this->belongsTo(Marketing::class);
    }

    public function penyedia_sampling() {
        return $this->hasOne(Penyedia_Sampling::class);
    }
}
