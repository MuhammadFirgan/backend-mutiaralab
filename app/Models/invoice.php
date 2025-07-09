<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    protected $guarded = ["id"];

    public function document() {
        return $this->belongsTo(Document::class);
    }
    
    public function poTtdQuotation() {
        return $this->belongsTo(Po_ttd_quotation::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
}
