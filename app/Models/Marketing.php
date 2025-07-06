<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class marketing extends Model
{
    protected $guarded = ["id"];

    public function document() {
        return $this->hasMany(Document::class);    
    }
}
