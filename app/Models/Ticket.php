<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'tickets';

    protected $fillable = ['event_id', 'ticket_name', 'ticket_price', 'quantity'];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
}
