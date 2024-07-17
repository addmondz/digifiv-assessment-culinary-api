<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unique_code',
        'chef_id',
        'ingredients',
        'likes_count',
        'dislikes_count',
    ];

    protected $casts = [
        'ingredients' => 'array',
    ];

    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_recipe');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tag');
    }
}
