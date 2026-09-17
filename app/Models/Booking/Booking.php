<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Hotel\Room;
use App\Models\Hotel\RoomService;
use App\Models\Invoice;
use App\Models\Review;

#[Table(keyType: 'string', incrementing: false)]
class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'check_in_date',
        'check_out_date',
        'status',
        'total_price',
        'deposit_amount',
        'id_document_type',
        'id_document_number',
        'guests_count',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    /**
     * A booking has several related records (rooms pivot, guests, payments,
     * actions, room services, invoice, review). The database enforces
     * foreign key constraints and refuses to delete a booking while
     * "child" rows still reference it. This hook cleans those up first,
     * automatically, no matter WHERE the delete is triggered from
     * (Filament admin, the API controller, tinker, etc.) — so the fix
     * only has to live in one place.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Booking $booking) {
            $booking->rooms()->detach();
            $booking->guests()->delete();
            $booking->actions()->delete();
            $booking->roomServices()->delete();
            $booking->payments()->delete();
            $booking->invoice()->delete();
            $booking->review()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'booking_rooms');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(BookingAction::class);
    }

    public function roomServices(): HasMany
    {
        return $this->hasMany(RoomService::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
