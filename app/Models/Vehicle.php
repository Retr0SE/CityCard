<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'transport_type_id', 'vehicle_number'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function transportType()
    {
        return $this->belongsTo(TransportType::class);
    }
}