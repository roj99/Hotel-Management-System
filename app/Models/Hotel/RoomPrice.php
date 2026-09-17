<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(keyType: 'string', incrementing: false)]
class RoomPrice extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'room_type_id',
        'start_date',
        'end_date',
        'price_per_night',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_per_night' => 'decimal:2',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomsType::class, 'room_type_id');
    }
}
