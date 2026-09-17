<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\RestrictedPageToRoles;
use App\Filament\Resources\Staff\Housekeepings\HousekeepingResource;
use App\Filament\Resources\Hotel\RoomServices\RoomServiceResource;
use App\Filament\Resources\Booking\Bookings\BookingResource;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use RestrictedPageToRoles;

    /**
     * لازم نضيف كل الأدوار المخصصة هون، وإلا canAccess() بترجع false
     * إلها وبيطلع 403 وقت الدخول لـ /admin (حتى قبل ما يوصل التحويل
     * التلقائي لصفحتهم عن طريق homeUrl).
     */
    protected static function allowedRoles(): array
    {
        return ['housekeeping', 'room_service', 'receptionist'];
    }

    /**
     * بمجرد ما تفتح /admin، إذا الدور مش admin/manager، حوّليه فورًا
     * لصفحته المخصصة بدل ما يشوف Dashboard الفاضية.
     */
    public function mount(): void
    {
        $roleName = auth()->user()?->role?->name;

        $url = match ($roleName) {
            'housekeeping' => HousekeepingResource::getUrl('index'),
            'room_service' => RoomServiceResource::getUrl('index'),
            'receptionist' => BookingResource::getUrl('index'),
            default => null,
        };

        if ($url) {
            redirect($url);
            return;
        }

        if (method_exists(parent::class, 'mount')) {
            parent::mount();
        }
    }
}
