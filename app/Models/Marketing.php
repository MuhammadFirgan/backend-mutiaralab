<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class marketing extends Model
{
    protected $guarded = ["id"];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function document() {
        return $this->belongsTo(Document::class);    
    }

    public function koorteknis() {
        return $this->hasMany(koor_teknis::class);
    }

    public function penyedia_sampling() {
        return $this->hasOne(penyedia_sampling::class);
    }

    public function quotation() {
        return $this->hasOne(quotation::class);
    }
}
