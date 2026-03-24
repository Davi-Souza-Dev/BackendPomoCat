<?php

namespace App\Actions\Analytic;

use App\Enums\FocusSessionStatus;
use App\Models\FocusSession;
use App\Models\User;

class GetHeatmap
{
    public function execute(User $user)
    {
        date_default_timezone_set('UTC');
        $actualDay = date('Y-m-d');
        $days = FocusSession::where('date', '<=', $actualDay)->where('user_id', $user->id)->where('status', FocusSessionStatus::COMPLETED)->limit(100)->orderBy('date', 'ASC')->pluck('date')->unique()->values()->toArray();
        return $days;
    }
}
