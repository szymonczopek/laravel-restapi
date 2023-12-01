<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'name',
        'photoUrls',
        'status',
        'category_id'
    ];

    protected $casts = [
        'photoUrls' => 'json',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'pet_has_tag','pet_id','tag_id');
    }
}
