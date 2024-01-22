<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'type', 'date', 'price', 'place', 'image_path'];

    // Define a relationship with the EventType model
    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'type');
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
