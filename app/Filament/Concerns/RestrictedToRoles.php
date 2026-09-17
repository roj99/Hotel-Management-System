<?php

namespace App\Filament\Concerns;

trait RestrictedToRoles
{
    /**
     * أسماء الأدوار المسموحلها تشوف/تدخل هالـ Resource (غير admin/manager،
     * هدول مسموحلهم دايماً تلقائياً). عرّفيها بكل Resource هيك:
     *
     * protected static function allowedRoles(): array
     * {
     *     return ['room_service'];
     * }
     */
    protected static function allowedRoles(): array
    {
        return [];
    }

 protected static function currentUserHasAccess(): bool
{
    $user = auth()->user();

    if (! $user || ! $user->role) {
        return false;
    }

    $roleName = $user->role->name;

    return in_array($roleName, ['admin', 'manager'], true)
        || in_array($roleName, static::allowedRoles(), true);
}

    public static function shouldRegisterNavigation(): bool
    {
        return static::currentUserHasAccess();
    }

    public static function canViewAny(): bool
    {
        return static::currentUserHasAccess();
    }

    public static function canView($record): bool
    {
        return static::currentUserHasAccess();
    }

    public static function canCreate(): bool
    {
        return static::currentUserHasAccess();
    }

    public static function canEdit($record): bool
    {
        return static::currentUserHasAccess();
    }

    public static function canDelete($record): bool
    {
        return static::currentUserHasAccess();
    }
}
