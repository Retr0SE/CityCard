<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPrice extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'transport_type_id', 'price'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function transportType()
    {
        return $this->belongsTo(TransportType::class);
    }
}