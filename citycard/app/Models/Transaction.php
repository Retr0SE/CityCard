<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'amount',
        'transaction_type',
        'vehicle_id'
    ];
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
