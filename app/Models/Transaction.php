<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'tenant_id',
        'type',
        'amount',
        'due_date',
        'status',
        'photo'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
