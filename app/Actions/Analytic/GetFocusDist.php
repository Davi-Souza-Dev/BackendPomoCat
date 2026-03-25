<?php

namespace App\Actions\Analytic;

use App\Enums\FocusSessionStatus;
use App\Models\FocusSession;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class GetFocusDist
{
    public function execute(User $user,$offset)
    {
        $actualWeek = Carbon::now()->subWeeks($offset);
        $startOfWeek = $actualWeek->copy()->startOfWeek(0)->format('Y-m-d');
        $endOfWeek = $actualWeek->copy()->endOfWeek(-1)->format('Y-m-d');
        $sessions = FocusSession::where('user_id', $user->id)
            ->where('status', FocusSessionStatus::COMPLETED)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->select('date', DB::raw('SUM(duration) as min'))
            ->groupBy('date')
            ->pluck('min','date');

        $chartData = [];
        $total = 0;
        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
        
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $data = $sessions[$formattedDate] ?? 0;
            $chartData[] = intval($data);
            $total += $data;
        }


        $startWeekDay = $actualWeek->copy()->startOfWeek(0)->format('d');
        $endWeekDay = $actualWeek->copy()->endOfWeek(-1)->format('d');
        $month = $actualWeek->copy()->format('M');
        $year = $actualWeek->copy()->format('Y');

        return [
            'title' => "$startWeekDay - $endWeekDay de $month. de $year",
            'data' => $chartData,
            'total' => $total,
        ];

    }
}
