<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Property extends Model
{
    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    private function generate_code(): string
    {
        $part1 = strtoupper(Str::random(3));
        $part2 = strtoupper(Str::random(3));

        return "{$part1}-{$part2}";
    }
}
