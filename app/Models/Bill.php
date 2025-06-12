<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'tenant_id',
        'amount_due',
        'due_date',
        'status',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
