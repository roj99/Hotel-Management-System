<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(keyType: 'string', incrementing: false)]
class RoomImage extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'room_type_id',
        'image_path',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomsType::class, 'room_type_id');
    }
}
