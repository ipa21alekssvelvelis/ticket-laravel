<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldTickets extends Model
{
    use HasFactory;
    protected $table = 'soldtickets';

    protected $fillable = ['user_id', 'event_id', 'ticket_id', 'quantity', 'payment_intent_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
