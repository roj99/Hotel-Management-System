<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Booking\Booking;
use App\Models\Staff\Housekeeping;
use App\Models\Staff\MaintenanceReport;
use App\Models\Staff\LostFoundItem;

#[Table(keyType: 'string', incrementing: false)]
class Room extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'room_type_id',
        'room_number',
        'status',
        'floor',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomsType::class, 'room_type_id');
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_rooms');
    }

    public function housekeepingTasks(): HasMany
    {
        return $this->hasMany(Housekeeping::class);
    }

    public function maintenanceReports(): HasMany
    {
        return $this->hasMany(MaintenanceReport::class);
    }

    public function lostFoundItems(): HasMany
    {
        return $this->hasMany(LostFoundItem::class);
    }
}
