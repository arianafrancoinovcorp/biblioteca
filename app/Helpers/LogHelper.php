<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogHelper
{
    public static function record($module, $objectId, $action)
    {
        $user = Auth::user();
        $userAgent = Request::header('User-Agent');

        Log::create([
            'user_id' => $user ? $user->id : null,
            'module' => $module,
            'object_id' => $objectId,
            'action' => $action,
            'ip' => Request::ip(),
            'browser' => $userAgent,
        ]);
    }
}
