<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{

    /** @use HasFactory<\Database\Factories\MaintenanceRequestFactory> */
    use HasFactory;
    protected $fillable = [
        'tenant_id',
        'room_id',
        'title',
        'description',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function scopeRelevantToTenant($query, Tenant $tenant)
    {
        return $query->where('tenant_id', $tenant->id)
            ->where('room_id', $tenant->room_id);
    }
}
