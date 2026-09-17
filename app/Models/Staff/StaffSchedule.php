<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

#[Table(keyType: 'string', incrementing: false)]
class StaffSchedule extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'staff_schedule';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'shift_date',
        'shift_start',
        'shift_end',
        'status',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'shift_start' => 'datetime',
        'shift_end' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
