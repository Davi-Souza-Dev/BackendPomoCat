<?php

namespace App\Actions\Analytic;

use App\Models\FocusSession;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class GetFocusDist
{
    public function execute(User $user,$offset)
    {
        date_default_timezone_set('UTC');
        date_default_timezone_set('America/Sao_Paulo');
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

        $actualWeek = Carbon::now()->subWeeks($offset);
        $startOfWeek = $actualWeek->copy()->startOfWeek(0)->format('Y-m-d');
        $endOfWeek = $actualWeek->copy()->endOfWeek(-1)->format('Y-m-d');
        $sessions = FocusSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->select('date', DB::raw('SUM(duration) as min'))
            ->groupBy('date')
            ->get()
            ->pluck('min', 'date');

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
