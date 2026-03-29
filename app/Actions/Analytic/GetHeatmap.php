<?php

namespace App\Actions\Analytic;

use App\Enums\FocusSessionStatus;
use App\Models\FocusSession;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetHeatmap
{
    public function execute(User $user): Collection
    {
        $days = DB::table('focus_sessions')->select('date')->distinct()->where('date', '<=', now()->toDateString())->where('user_id', $user->id)->where('status', FocusSessionStatus::COMPLETED)->orderBy('date','ASC')->limit(100)->get()->pluck('date');
        return $days;
    }
}
