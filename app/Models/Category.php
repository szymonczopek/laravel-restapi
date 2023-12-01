<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public function pet()
    {
        return $this->hasMany(Pet::class,'category_id');
    }
}
