<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Staff\Housekeepings\HousekeepingResource;
use App\Filament\Resources\Hotel\RoomServices\RoomServiceResource;
use App\Filament\Resources\Booking\Bookings\BookingResource;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Hotel Management')
            ->favicon(asset('favicon.png'))
            ->login()
            //->authGuard('web')
            ->colors([

    'primary' => Color::hex('#263746'),
    'success' => Color::hex('#71856B'),
    'danger'  => Color::hex('#A96868'),
    'warning' => Color::hex('#B89B68'),
    'info'    => Color::hex('#6B7F8F'),
    'gray'    => Color::hex('#756E68'),

            ])
            /**
             * Filament (v4+) sorts navigation groups by the lowest
             * navigationSort of their items, not alphabetically — so
             * without this, "Settings" could jump anywhere in the sidebar.
             * Listing groups here in order pins them exactly as written.
             * If you add another navigationGroup name anywhere in the app
             * (a resource or page), add it to this list too — any group
             * NOT listed here may appear in an unpredictable position.
             */
            ->navigationGroups([
                'Users',
                'Hotel',
                'Operations',
                'Processes',
                'Reports',
                'Settings',
            ])
            /**
             * بعد تسجيل الدخول، كل دور بيوديه على "بيته" المناسبة بدل
             * الداشبورد الفاضي (لأنه Dashboard صارت admin/manager بس).
             * admin/manager (أو أي دور تاني ما إله صفحة مخصصة) بيوصل
             * للداشبورد العادية زي ما كان.
             */
            ->homeUrl(function () {
                $roleName = auth()->user()?->role?->name;

                return match ($roleName) {
                    'housekeeping' => HousekeepingResource::getUrl('index'),
                    'room_service' => RoomServiceResource::getUrl('index'),
                    'receptionist' => BookingResource::getUrl('index'),
                    default => Dashboard::getUrl(),
                };
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
    AccountWidget::class,
    FilamentInfoWidget::class,
    \App\Filament\Widgets\ReportsOverview::class,
])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

    }
}
