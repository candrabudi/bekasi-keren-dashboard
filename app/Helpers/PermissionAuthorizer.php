<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionAuthorizer
{
    public static function check(string $permission): void
    {
        $user = Auth::user();

        if (!$user || !$user->can($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }
    }
}
