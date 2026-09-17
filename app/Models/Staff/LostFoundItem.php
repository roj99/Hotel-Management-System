<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Hotel\Room;
use App\Models\User;

#[Table(keyType: 'string', incrementing: false)]
class LostFoundItem extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lost_found_item';

    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'found_by',
        'item_description',
        'storage_location',
        'status',
        'found_at',
        'returned_at',
    ];

    protected $casts = [
        'found_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function foundBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'found_by');
    }
}
