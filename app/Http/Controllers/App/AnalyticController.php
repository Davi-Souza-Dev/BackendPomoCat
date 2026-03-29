<?php

namespace App\Http\Controllers\App;

use App\Actions\Analytic\GetFocusDist;
use App\Actions\Analytic\GetHeatmap;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Throwable;

class AnalyticController extends Controller
{
    public function index(Request $request, GetHeatmap $getHeatmap, GetFocusDist $getFocusDist)
    {
        try {
            $user = Auth::user();
            return Inertia::render('Analytic', ['heatDates' => $getHeatmap->execute($user), 'week' => $getFocusDist->execute($user,0)]);
        } catch (Throwable $error) {
            return redirect()->route('auth.loginForm');
        }
    }

    public function distgraph(Request $request, GetFocusDist $getFocusDist)
    {
        try {
            return $getFocusDist->execute(Auth::user(),$request->offsetweek);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }
}
