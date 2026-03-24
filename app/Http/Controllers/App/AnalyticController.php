<?php

namespace App\Http\Controllers\App;

use App\Actions\Analytic\GetFocusDist;
use App\Actions\Analytic\GetFocusDisto;
use App\Actions\Analytic\GetHeatmap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Throwable;

class AnalyticController extends Controller
{
    public function analytics(Request $request,GetHeatmap $getHeatmap,GetFocusDist $getFocusDist){
        try{
            $user = Auth::user();

            return Inertia::render('Analytic',['heatDates'=>$getHeatmap->execute($user),"week" => $getFocusDist->execute($user)]);
        }catch(Throwable $error){
            dd($error);
            // return redirect()->route('auth.loginForm');
        }
    }
}
