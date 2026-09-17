<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Hotel\RoomsType;

#[Table(keyType: 'string', incrementing: false)]
class Amenity extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
    ];

    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomsType::class, 'room_type_has_amenities', 'amenity_id', 'room_type_id');
    }
}
