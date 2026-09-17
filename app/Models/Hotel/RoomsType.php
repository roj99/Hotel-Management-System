<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Amenity;

#[Table(keyType: 'string', incrementing: false)]
class RoomsType extends Model
{
    use HasFactory, HasUuids;


    protected $fillable = [
        'name',
        'description',
        'capacity',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class, 'room_type_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(RoomPrice::class, 'room_type_id');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_type_has_amenities', 'room_type_id', 'amenity_id');
    }
}
