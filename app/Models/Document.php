<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = ["id"];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function marketing() {
        return $this->hasOne(Marketing::class);
    }

    public function penyedia_sampling() {
        return $this->hasOne(penyedia_sampling::class);
    }


}
