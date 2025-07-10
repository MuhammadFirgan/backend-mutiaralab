<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Po_ttd_quotation extends Model
{
    protected $guarded = ["id"];

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function quotation() {
        return $this->belongsTo(Quotation::class);
    }

    public function invoice() {
        return $this->hasOne(Invoice::class);
    }
}
