<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;


    protected $fillable = [
        'landlord_id',
        'code',
        'title',
        'description',
        'address',
        'rent_amount',
        'max_occupancy',
        'current_occupancy',
    ];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
