<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];

    public function pet()
    {
        return $this->belongsToMany(Pet::class,'pet_has_tag','tag_id','pet_id');
    }
}
