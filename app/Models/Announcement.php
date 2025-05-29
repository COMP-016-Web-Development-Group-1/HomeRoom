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
}
