<?php

namespace App\Filament\Concerns;

trait RestrictedPageToRoles
{
    /**
     * أسماء الأدوار المسموحلها تشوف/تدخل هالصفحة (غير admin/manager،
     * هدول مسموحلهم دايماً تلقائياً). نفس مبدأ RestrictedToRoles بالضبط،
     * بس لصفحات Filament (Page) مش الـ Resources.
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

    /**
     * canAccess() لوحدها كافية عند Filament - بتمنع الظهور بالقائمة
     * الجانبية وبتمنع الدخول المباشر بالرابط بنفس الوقت.
     */
    public static function canAccess(): bool
    {
        return static::currentUserHasAccess();
    }
}
