<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'images',
        'photo',
        'start_date',
        'end_date',
        'visibility',
        'active',
    ];

    protected $casts = [
        'images' => 'array',
        'active' => 'boolean',
        'visibility' => 'boolean',
    ];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'event_recipe')
            ->withTimestamps();
    }

    public function getDisplayNameAttribute()
    {
        return $this->name ?: $this->title;
    }
}
