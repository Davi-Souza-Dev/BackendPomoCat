<?php

namespace App\Actions\Analytic;

use App\Enums\FocusSessionStatus;
use App\Models\FocusSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class GetTodayFocus
{
    public function execute(User $user)
    {
        date_default_timezone_set('UTC');
        $actualDay = date('Y-m-d');
        $todayfocus = FocusSession::where('user_id', $user->id)
            ->where('status', FocusSessionStatus::COMPLETED)
            ->where('date', $actualDay)
            ->select('date', DB::raw('SUM(duration) as min'))
            ->groupBy('date')
            ->pluck('min');
        return $todayfocus[0] ?? 0;
    }
}
