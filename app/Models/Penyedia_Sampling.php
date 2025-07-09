<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penyedia_sampling extends Model
{

    protected $guarded = ["id"];

    public function document() {
        return $this->belongsTo(document::class);
    }

    public function marketing() {
        return $this->belongsTo(marketing::class);
    }

    public function koor_teknis() {
        return $this->belongsTo(koor_teknis::class);
    }

}
