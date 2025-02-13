<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'json'
    ];


    public function Tenant(){
        return $this->belongsTo(Tenant::class);
    }
}
