<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingAction;
use App\Models\Staff\StaffSchedule;
use App\Models\Staff\Housekeeping;
use App\Models\Staff\MaintenanceReport;
use App\Models\Staff\LostFoundItem;
use App\Models\Hotel\RoomService;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Table(keyType: 'string', incrementing: false)]
class User extends Authenticatable implements JWTSubject, FilamentUser
{
    use HasFactory, HasUuids, Notifiable;

    protected $fillable = [
        'role_id',
        'full_name',
        'email',
        'phone',
        'password',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'=>'hashed'
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($user) {

         if (is_null($user->name)) {
             $user->name = $user->full_name ?? 'User';
            }

        if (is_null($user->full_name)) {
                $user->full_name = $user->name ?? 'New User';
            }

        if (is_null($user->phone)) {
                $user->phone = '0000000000';
            }
                });
}


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function staffSchedules(): HasMany
    {
        return $this->hasMany(StaffSchedule::class);
    }

    public function housekeepingTasks(): HasMany
    {
        return $this->hasMany(Housekeeping::class, 'staff_id');
    }

    public function maintenanceReportsReported(): HasMany
    {
        return $this->hasMany(MaintenanceReport::class, 'reported_by');
    }

    public function maintenanceReportsResolved(): HasMany
    {
        return $this->hasMany(MaintenanceReport::class, 'resolved_by');
    }

    public function lostFoundItemsFound(): HasMany
    {
        return $this->hasMany(LostFoundItem::class, 'found_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function roomServicesHandled(): HasMany
    {
        return $this->hasMany(RoomService::class, 'handled_by');
    }

    public function bookingActionsPerformed(): HasMany
    {
        return $this->hasMany(BookingAction::class, 'performed_by');
    }

    /**
     * حسابات فئة "guest" (زباين الموقع العام) ممنوعين كلياً من الدخول
     * عـ /admin - هاي لوحة الموظفين بس. باقي الأدوار (admin, manager,
     * receptionist, housekeeping, room_service) مسموحلهم يدخلوا، وبعدين
     * كل Resource بيقرر لحاله مين بالضبط بيشوفه (شوف RestrictedToRoles).
     */
 public function canAccessPanel(Panel $panel): bool
{
    return true;
}

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getFilamentName(): string
{
    return $this->full_name ?? $this->name ?? 'User';
}
}
