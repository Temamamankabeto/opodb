<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\KebeleCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WoredaController extends Controller
{
    public function woredaCropPlans()
    {
        $user = Auth::user();

        // Assuming Woreda admin has a 'woreda_id' column
        $kebeles = Kebele::where('woreda_id', $user->woreda_id)->pluck('id');

        // Pending crop plans across kebeles
        $pendingCropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele'])
            ->whereIn('kebele_id', $kebeles)
            ->where('status', 'pending')
            ->orderBy('year', 'desc')
            ->get();

        // History crop plans
        $historyCropPlans = KebeleCropPlan::with(['cropType', 'crop', 'kebele'])
            ->whereIn('kebele_id', $kebeles)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('approved_at', 'desc')
            ->get();

        return view('woreda.crop-approval', compact('pendingCropPlans', 'historyCropPlans'));
    }
}
