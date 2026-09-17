<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Booking\Booking;
use App\Models\User;

#[Table(keyType: 'string', incrementing: false)]
class RoomService extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'handled_by',
        'item_description',
        'quantity',
        'price',
        'status',
        'ordered_at',
        'delivered_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'ordered_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
