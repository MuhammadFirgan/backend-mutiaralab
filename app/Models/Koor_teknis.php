<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class koor_teknis extends Model
{

    protected $guarded = ["id"];

    public function marketing() {
        return $this->belongsTo(marketing::class);
    }
}
