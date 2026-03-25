<?php

namespace App\Actions\Analytic;

use App\Enums\FocusSessionStatus;
use App\Models\FocusSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GetHeatmap
{
    public function execute(User $user)
    {
        date_default_timezone_set('UTC');
        $actualDay = date('Y-m-d');
        $days = DB::table('focus_sessions')->select('date')->where('date', '<=', $actualDay)->where('user_id', $user->id)->where('status', FocusSessionStatus::COMPLETED)->orderBy('date','ASC')->limit(100)->get()->pluck('date');
        return $days;
    }
}
