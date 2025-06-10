<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /** @use HasFactory<\Database\Factories\AnnouncementFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'room_id',
        'title',
        'description',
        'type',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function isSystemWide(): bool
    {
        return is_null($this->property_id) && is_null($this->room_id);
    }

    public function isPropertyWide(): bool
    {
        return ! is_null($this->property_id) && is_null($this->room_id);
    }

    public function isRoomSpecific(): bool
    {
        return ! is_null($this->property_id) && ! is_null($this->room_id);
    }

    public function scopeRelevantToRoom($query, Room $room)
    {
        return $query->where(function ($query) use ($room) {
            $query->whereNull('property_id') // system-wide
                ->orWhere(function ($query) use ($room) {
                    $query->where('property_id', $room->property_id)
                        ->where(function ($q) use ($room) {
                            $q->whereNull('room_id')     // property-wide
                                ->orWhere('room_id', $room->id); // room-specific
                        });
                });
        });
    }

    public function scopeRelevantToTenant($query, $tenant)
    {
        // Get property_id and room_id from tenant's room (if available)
        $room = $tenant->room;
        $propertyId = $room ? $room->property_id : null;
        $roomId = $room ? $room->id : null;

        // Show:
        // 1. System announcements (no property and no room)
        // 2. Property-wide announcements (for tenant's property, room_id null)
        // 3. Room-specific announcements (for tenant's room)
        return $query->where(function ($q) use ($propertyId, $roomId) {
            $q->where(function ($q2) {
                $q2->whereNull('property_id')->whereNull('room_id'); // system
            })->orWhere(function ($q2) use ($propertyId) {
                $q2->where('property_id', $propertyId)->whereNull('room_id'); // property
            })->orWhere(function ($q2) use ($propertyId, $roomId) {
                $q2->where('property_id', $propertyId)->where('room_id', $roomId); // room
            });
        });
    }
}
