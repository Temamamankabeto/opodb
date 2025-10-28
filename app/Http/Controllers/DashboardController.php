<?php

namespace App\Http\Controllers;

use App\Models\KarorQonnaWoggaa;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function kebeleDashboard()
    {
        return view('components.dashboard.kebele');
    }
    public function sectorDashboard()
    {
        return view('components.dashboard.sector');
    }


    public function woredaDashboard()
    {
        $user = auth()->user(); // Get currently logged-in user

        if ($user->role_id != 3) {
            abort(403, 'Unauthorized access'); // or redirect to another page
        }

        return view('components.dashboard.woreda');
    }

    public function zoneDashboard()
    {
        $user = Auth::user();

        // Get the zone assigned to logged-in user
        $zone = Zone::with(['woredas.kebeles'])->find($user->zone_id);

        if (!$zone) {
            abort(403, 'No zone assigned to your account.');
        }

        // Total reports in the user's zone
        $totalReports = KarorQonnaWoggaa::where('zone_id', $zone->id)->count();

        // Recent reports for user's zone
        $recentReports = KarorQonnaWoggaa::with('user')
            ->where('zone_id', $zone->id)
            ->latest()
            ->take(2)
            ->get();

        // Prepare chart data
        $zoneLabels = [$zone->name];
        $zoneLandData = [$zone->woredas->flatMap->kebeles->sum('total_area')];
        $zoneWoredaCount = [$zone->woredas->count()];
        $zoneKebeleCount = [$zone->woredas->flatMap->kebeles->count()];
        $zoneProductionData = [
            KarorQonnaWoggaa::where('zone_id', $zone->id)->sum('production')
        ];

        return view('components.dashboard.zone', compact(
            'totalReports',
            'recentReports',
            'zoneLabels',
            'zoneLandData',
            'zoneProductionData',
            'zoneWoredaCount',
            'zoneKebeleCount'
        ));
    }
    public function regionDashboard()
    {
        $user = auth()->user(); // Get the currently authenticated user

        // Check if user is not role_id 1
        if ($user->role_id != 1) {
            abort(403, 'Unauthorized access'); // Or redirect to another page
        }

        $zones = Zone::with(['woredas.kebeles'])->get();

        $totalWoredas = $zones->flatMap->woredas->count();
        $totalKebeles = $zones->flatMap->woredas->flatMap->kebeles->count();
        $totallandarea = $zones->flatMap->woredas->flatMap->kebeles->sum('total_area');

        $reportData = KarorQonnaWoggaa::with(['zone', 'woreda', 'cropType', 'crop'])
            ->get()
            ->groupBy(['zone_id', 'woreda_id', 'crop_type_id', 'crop_id']);

        // Chart Data Arrays
        $zoneLabels = [];
        $zoneLandData = [];
        $zoneProductionData = [];
        $zoneWoredaCount = [];
        $zoneKebeleCount = [];

        foreach ($zones as $zone) {
            $zoneLabels[] = $zone->name;
            $zoneLandData[] = $zone->woredas->flatMap->kebeles->sum('total_area');
            $zoneWoredaCount[] = $zone->woredas->count();
            $zoneKebeleCount[] = $zone->woredas->flatMap->kebeles->count();

            // Production per zone from crop reports
            $production = KarorQonnaWoggaa::where('zone_id', $zone->id)->sum('production');
            $zoneProductionData[] = $production;
        }

        return view('components.dashboard.region', compact(
            'zones',
            'totalWoredas',
            'totalKebeles',
            'totallandarea',
            'reportData',
            'zoneLabels',
            'zoneLandData',
            'zoneProductionData',
            'zoneWoredaCount',
            'zoneKebeleCount'
        ));
    }
}
