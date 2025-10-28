<?php

namespace App\Http\Controllers;

use App\Models\KebeleCropPlan;
use App\Models\KebeleEconomicPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebeleAdminController extends Controller
{
    public function dashboard()
    {
        $kebeleId = auth()->user()->kebele_id;

        $pendingCropPlans = KebeleCropPlan::with(['crop', 'cropType'])
            ->where('kebele_id', $kebeleId)
            ->where('status', 'pending')
            ->get();

        $pendingEconomicPlans = KebeleEconomicPlan::where('kebele_id', $kebeleId)
            ->where('status', 'pending')
            ->get();

        $historyCropPlans = KebeleCropPlan::with(['crop', 'cropType'])
            ->where('kebele_id', $kebeleId)
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at')
            ->get();

        $historyEconomicPlans = KebeleEconomicPlan::where('kebele_id', $kebeleId)
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at')
            ->get();

        return view('kebele.admin.dashboard', compact(
            'pendingCropPlans',
            'pendingEconomicPlans',
            'historyCropPlans',
            'historyEconomicPlans'
        ));
    }

    


    public function approve($sector, $id)
    {
        $model = match ($sector) {
            'crop' => KebeleCropPlan::class,
            'economic' => KebeleEconomicPlan::class,
            default => null,
        };

        if (!$model) {
            return back()->with('error', 'Invalid sector type.');
        }

        $plan = $model::findOrFail($id);
        $plan->status = 'Approved';
        $plan->approved_by = Auth::id();
        $plan->save();

        return back()->with('success', ucfirst($sector) . ' plan approved successfully!');
    }

    public function reject($sector, $id)
    {
        $model = match ($sector) {
            'crop' => KebeleCropPlan::class,
            'economic' => KebeleEconomicPlan::class,
            default => null,
        };

        if (!$model) {
            return back()->with('error', 'Invalid sector type.');
        }

        $plan = $model::findOrFail($id);
        $plan->status = 'Rejected';
        $plan->approved_by = Auth::id();
        $plan->save();

        return back()->with('error', ucfirst($sector) . ' plan rejected.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'sector' => 'required|string',
            'id' => 'required|integer',
            'status' => 'required|in:approved,rejected',
        ]);

        $model = match ($request->sector) {
            'crop' => KebeleCropPlan::class,
            'economic' => KebeleEconomicPlan::class,
            default => abort(404, 'Invalid sector'),
        };

        $plan = $model::findOrFail($request->id);
        $plan->status = $request->status;
        $plan->approved_by = auth()->id();
        $plan->approved_at = now();
        $plan->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($request->sector) . " plan {$request->status} successfully.",
        ]);
    }
}
