<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Po_ttd_quotation extends Model
{
    protected $guarded = ["id"];

    public function document() {
        return $this->belongsTo(document::class);
    }

    public function quotation() {
        return $this->belongsTo(quotation::class);
    }

    public function invoice() {
        return $this->hasOne(invoice::class);
    }
}
