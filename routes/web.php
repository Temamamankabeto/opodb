<?php

use App\Models\Kunuunsa;
use App\Models\YealrlyCropPlan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Controllers\QonnaController;
use App\Http\Controllers\UnionController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\HaamaaController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\KaroorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrchardController;
use App\Http\Controllers\FacaasaaController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\KunuunsaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LiveStockController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\CreditDataController;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\WeeklyPlanController;
use App\Http\Controllers\AssociationController;
use App\Http\Controllers\CompostTypeController;
use App\Http\Controllers\JobCreationController;
use App\Http\Controllers\KebeleAdminController;
use App\Http\Controllers\MonthlyPlanController;
use App\Http\Controllers\NurserySiteController;
use App\Http\Controllers\ConstructionController;
use App\Http\Controllers\QabenyaUmamaController;
use App\Http\Controllers\SeedlingTypeController;


use App\Http\Controllers\GabbinaBiyyeeController;
use App\Http\Controllers\MaterialSuplyController;
use App\Http\Controllers\MisomaBeyladaController;
use App\Http\Controllers\ZonalDevisionController;
use App\Http\Controllers\EggumsaNaannooController;
use App\Http\Controllers\JobOpportunityController;
use App\Http\Controllers\KebeleCropPlanController;
use App\Http\Controllers\WeeklyLandPlanController;
use App\Http\Controllers\WeeklyTrackingController;
use App\Http\Controllers\YearlyCropPlanController;
use App\Http\Controllers\LivestockSupplyController;
use App\Http\Controllers\NurserySeedlingController;
use App\Http\Controllers\VillageResourceController;
use App\Http\Controllers\ForestManagementController;
use App\Http\Controllers\AgriculturalInputController;
use App\Http\Controllers\CivilRegistrationController;
use App\Http\Controllers\CulturalRewalArtDevelopment;
use App\Http\Controllers\OrchardProductionController;
use App\Http\Controllers\WatershedResourceController;
use App\Http\Controllers\EconomicTransitionController;
use App\Http\Controllers\KebeleEconomicPlanController;



use App\Http\Controllers\SocialDevelopementController;
use App\Http\Controllers\CooperativeActivityController;
use App\Http\Controllers\CropPlanAchievementController;
use App\Http\Controllers\LivestockProductionController;
use App\Http\Controllers\NewConstructionPlanController;
use App\Http\Controllers\RurarGovermentAssetController;
use App\Http\Controllers\SeedlingPreparationController;
use App\Http\Controllers\InstitutionImprovementController;
use App\Http\Controllers\ConstructionMaintenanceController;
use App\Http\Controllers\UpgradingConstructionPlanController;
 

Route::middleware([SecurityHeaders::class])->group(function () {
    // Public routes

    Route::get('/', [UserController::class, 'login'])->name('users.login');
    Route::post('/checkuser', [UserController::class, 'checkuser'])->name('user.check');
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'checkuser'])->name('user.check');


    // Protected routes (must be logged in)
    Route::middleware('auth')->group(function () {
        // Super Admin only

        Route::middleware('role:1')->prefix('admin')->group(function () {

            Route::get('kebele-list', [StructureController::class, 'kebelelist'])->name('kebeles.list');

            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('user/create-account', [UserController::class, 'create'])->name('users.create');
            Route::get('user/edit-account/{id}', [UserController::class, 'edit'])->name('users.edit');
            Route::delete('user/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('woreda-users', [UserController::class, 'woredaadmin'])->name('woreda.users.index');
            Route::get('kebele-users', [UserController::class, 'kebeleadmin'])->name('admin.kebele.users.index');
            Route::get('users/sector-user', [UserController::class, 'sectoruser'])->name('sector.users.index');
            Route::post('users/save/sector-user', [UserController::class, 'storesectoradmin'])->name('users.sector.admin.store');
            Route::post('user/save', [UserController::class, 'store'])->name('users.store');
            Route::post('user-kebele/save', [UserController::class, 'kebeleadminstore'])->name('users.kebele.admin.store');

            Route::get('get-woredas/{zone_id}', [UserController::class, 'getWoredas']);
            Route::get('get-kebeles/{woreda_id}', [UserController::class, 'getKebeles']);
            Route::post('kebeles', [StructureController::class, 'storekebele'])->name('admin.kebeles.store');

            Route::get('zones', [StructureController::class, 'zonelist'])->name('admin.zones.index');
            Route::post('zones/save', [StructureController::class, 'storezone'])->name('admin.zones.store');
            Route::put('kebeles/{kebele}', [StructureController::class, 'update'])->name('admin.kebeles.update');

            Route::put('update/woreda/{woreda}', [StructureController::class, 'updateworeda'])->name('admin.woreda.update');

            // Regional reports and related routes
            Route::get('region/reports/job', [JobCreationController::class, 'jobReportregion'])
                ->name('region.reports.job_report')
                ->middleware(['auth']);
            Route::get('region-dashboard', [DashboardController::class, 'regionDashboard'])->name('region.dashboard');
            Route::get('reports/region-crop-analysis', [KaroorController::class, 'regionCropAnalysis'])
                ->name('reports.region-crop-analysis')
                ->middleware('auth');
            Route::get('weekly-plans/land/region', [WeeklyLandPlanController::class, 'indexregion'])
                ->name('weekly-plans.land.index.region');
            Route::post('user/save', [UserController::class, 'store'])->name('admin.user.store');

            Route::get('woreda/users', [UserController::class, 'woredaUsers'])->name('woreda.users');
            Route::get('woredas', [StructureController::class, 'woredalist'])->name('woredas.index');
            Route::post('/woreda/save', [StructureController::class, 'storeworeda'])->name('admin.woreda.store');


            Route::get('profile/{uuid}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('profile/{uuid}', [UserController::class, 'update'])->name('admin.users.update');
            Route::get('/profile/users/{uuid}', [UserController::class, 'profileedit'])->name('admin.users.edit');

            Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('admin.users.profile');
            Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('admin.users.password');
            Route::post('/change-password', [UserController::class, 'changePassword'])->name('password.change');
        });

        // Zone Admin only
        Route::middleware('role:2')->prefix('zones')->group(function () {

            // Route::get('users', [UserController::class, 'zoneUsers'])->name('zone.users');
            Route::get('zone-list', [ZoneController::class, 'zoneOnlyList'])->name('zone.zones');
            Route::get('all-zones', [StructureController::class, 'zonelist'])->name('zones.index');
            Route::post('zones/save', [StructureController::class, 'storezone'])->name('zones.store');

            Route::get('woreda/users', [UserController::class, 'woredaUsers'])->name('woreda.users');
            Route::get('dashboard', [DashboardController::class, 'zoneDashboard'])->name('zone.dashboard');

            Route::get('users/sector-user', [UserController::class, 'sectoruser'])->name('sector.users.index');
            Route::get('woreda-admins', [UserController::class, 'woredaadmin'])->name('woreda.users.index');
            Route::get('kebele-admins', [UserController::class, 'kebeleadmin'])->name('kebele.users.index');

            Route::get('structures', [StructureController::class, 'structures'])->name('structure.index');
            Route::get('woreda/structures', [StructureController::class, 'woredastructures'])->name('woreda.structure.index');

            Route::get('woredas', [StructureController::class, 'woredalist'])->name('woredas.index');
            Route::post('/woredas/save', [StructureController::class, 'storeworeda'])->name('woredas.store');

            Route::get('kebele-list', [StructureController::class, 'kebelelist'])->name('kebeles.list');
            Route::post('kebeles', [StructureController::class, 'storekebele'])->name('kebeles.store');
            Route::get('kebeles/{kebele}/edit', [StructureController::class, 'edit'])->name('kebeles.edit');
            Route::put('kebeles/{kebele}', [StructureController::class, 'update'])->name('kebeles.update');
            Route::get('kebele-users', [UserController::class, 'kebeleadmin'])->name('zone.kebele.users.index');

            Route::put('update/woreda/{woreda}', [StructureController::class, 'updateworeda'])->name('zones.woreda.update');

            Route::post('sectors/store', [StructureController::class, 'kebelesectorstore'])->name('sectors.store');

            Route::post('user-kebele/save', [UserController::class, 'kebeleadminstore'])->name('users.kebele.admin.store');
            Route::post('user/save', [UserController::class, 'store'])->name('zones.susers.store');
            Route::get('woreda-users', [UserController::class, 'woredaadmin'])->name('zone.woreda.users.index');
            Route::post('user/save', [UserController::class, 'store'])->name('zone.user.store');

            Route::get('get-woredas/{zone_id}', [UserController::class, 'getWoredas']);
            Route::get('get-kebeles/{woreda_id}', [UserController::class, 'getKebeles']);


            Route::get('profile/{uuid}/edit', [UserController::class, 'edit'])->name('zone.users.edit');
            Route::put('profile/{uuid}', [UserController::class, 'update'])->name('zone.users.update');
            Route::get('/profile/users/{uuid}', [UserController::class, 'edit'])->name('zone.users.edit');

            Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('zone.profile.users');
            Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('zone.users.password');
            Route::post('/change-password', [UserController::class, 'changePassword'])->name('zone.password.change');
            Route::get('kebeleadmin/{uuid}/edit', [UserController::class, 'kebeleadminedit'])->name('zone.user.edit');
            Route::put('kebeleadminupdate/{uuid}/', [UserController::class, 'kebeleadminupdate'])->name('kebele.admin.update');
        });

        // Woreda Admin only
        Route::middleware('role:3')->prefix('woredas')->group(function () {
            Route::get('/woreda/users', [UserController::class, 'woredaUsers'])->name('woreda.users');
            // add more woreda admin routes here
            Route::get('/woreda-dashboard', [DashboardController::class, 'woredaDashboard'])->name('woreda.dashboard');
            Route::get('profile/{uuid}/edit', [UserController::class, 'edit'])->name('woreda.users.edit');
            Route::put('profile/{uuid}', [UserController::class, 'update'])->name('woreda.users.update');
            Route::get('/profile/users/{uuid}', [UserController::class, 'edit'])->name('woreda.users.edit');

            Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('woreda.profile.users');
            Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('woreda.users.password');
            Route::post('/change-password', [UserController::class, 'changePassword'])->name('woreda.password.change');

            Route::get('structures', [StructureController::class, 'woredastructures'])->name('woreda.structure.index');
        });

        // Kebele Admin only
        Route::middleware('role:4')->prefix('kebele')->group(function () {
            Route::get('/woreda/users', [UserController::class, 'woredaUsers'])->name('woreda.users');
            // add more woreda admin routes here
            Route::get('profile/{uuid}/edit', [UserController::class, 'edit'])->name('woreda.users.edit');
            Route::put('profile/{uuid}', [UserController::class, 'update'])->name('woreda.users.update');
            Route::get('/profile/users/{uuid}', [UserController::class, 'edit'])->name('woreda.users.edit');

            Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('users.profile');
            Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('users.password');
            Route::post('/change-password', [UserController::class, 'changePassword'])->name('password.change');


            Route::get('profile/{uuid}/edit', [UserController::class, 'edit'])->name('kebele.users.edit');
            Route::put('profile/{uuid}', [UserController::class, 'profileupdate'])->name('kebele.users.update');
            // Route::put('profile/{uuid}', [UserController::class, 'update'])->name('kebele.users.update');
            Route::get('/profile/users/{uuid}', [UserController::class, 'profileedit'])->name('kebele.users.edit');

            Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('kebele.profile.users');
            Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('kebele.users.password');
            Route::post('/change-password', [UserController::class, 'changePassword'])->name('kebele.password.change');
        });

        // Logout
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    });


    Route::group(['middleware' => 'auth'], function () {

        Route::get('/misooma/baadiyyaa', [ReportController::class, 'baadiyaa'])->name('misoma.baadiyaa');
        Route::get('/akaakuu/midhaanii  ', [ReportController::class, 'akaakuu'])->name('akaakuu.midhaanii');
        Route::get('/agada/two/plan', [ReportController::class, 'agada'])->name('karoora.agada ');
        Route::get('/type/{id}/plan-achievement', [ReportController::class, 'type'])->name('plan.achievement.type');
        Route::get('/karoora/midhaan/agadaa', [ReportController::class, 'karooraagada'])->name('karoora.midhaan.agada');

        Route::get('/karoora/beeylada', [ReportController::class, 'karoorabeeylada'])->name('karoora.beeylada');
        Route::get('/karoora/boqqolloo', [ReportController::class, 'karooraboqqoolloo'])->name('karoora.bpqqoolloo');


        Route::get('/crop-plan-achievements/karoora', [CropPlanAchievementController::class, 'index'])->name('crop_plan_achievements.index');
        Route::get('/crop-plan-achievements/rawwii', [CropPlanAchievementController::class, 'indexrawwii'])->name('crop_plan_achievements.raawwii');
        Route::get('/crop-plan-achievements/create', [CropPlanAchievementController::class, 'create'])->name('crop_plan_achievements.create');
        Route::post('/crop-plan-achievements', [CropPlanAchievementController::class, 'store'])->name('crop_plan_achievements.store');
        Route::get('/crop-plan-achievements/{id}/edit', [CropPlanAchievementController::class, 'edit'])->name('crop_plan_achievements.edit');
        Route::put('/crop-plan-achievements/{id}', [CropPlanAchievementController::class, 'update'])->name('crop_plan_achievements.update');
        Route::delete('/crop-plan-achievements/{id}', [CropPlanAchievementController::class, 'destroy'])->name('crop_plan_achievements.destroy');

        Route::get('/crop-plan-achievements/fetch_data', [CropPlanAchievementController::class, 'fetchData'])->name('crop_plan_achievements.fetch_data');


        Route::get('/get-crops-by-type/{crop_type_id}', [CropPlanAchievementController::class, 'getCropsByType']);
        Route::get('/get-crops-by-type/{crop_type_id}', [QonnaController::class, 'getCropsByType']);

        // Route in web.php
        Route::get('/previous-year-crop-data', [CropPlanAchievementController::class, 'getPreviousYearData']);


        // Qonna routes
        Route::get('/qonnas', [QonnaController::class, 'index'])->name('qonnas.index');
        Route::get('/qonnas/create', [QonnaController::class, 'create'])->name('qonnas.create');
        Route::post('/qonnas/store', [QonnaController::class, 'store'])->name('qonnas.store');
        Route::get('/qonnas/{id}/edit', [QonnaController::class, 'edit'])->name('qonnas.edit');
        Route::put('/qonnas/{qonna}', [QonnaController::class, 'update'])->name('qonnas.update');

        Route::delete('/qonnas/{id}/delete', [QonnaController::class, 'destroy'])->name('qonnas.destroy');
        Route::prefix('kunuunsas')->group(function () {
            Route::get('/', [KunuunsaController::class, 'index'])->name('kunuunsas.index');  // List all Kunuunsas
            Route::get('create/kununsa', [KunuunsaController::class, 'create'])->name('kunuunsas.create');  // Show form for new Kunuunsa
            Route::post('store', [KunuunsaController::class, 'store'])->name('kunuunsas.store');  // Store a new Kunuunsa
            Route::get('edit/{id}', [KunuunsaController::class, 'edit'])->name('kunuunsas.edit');  // Show form to edit a Kunuunsa
            Route::put('update/{id}', [KunuunsaController::class, 'update'])->name('kunuunsas.update');  // Update Kunuunsa
            Route::delete('destroy/{id}', [KunuunsaController::class, 'destroy'])->name('kunuunsas.destroy');  // Delete Kunuunsa
        });

        Route::get('facaasaas', [FacaasaaController::class, 'index'])->name('facaasaas.index');
        Route::get('facaasaas/create', [FacaasaaController::class, 'create'])->name('facaasaas.create');
        Route::post('facaasaas', [FacaasaaController::class, 'store'])->name('facaasaas.store');
        Route::get('facaasaas/{facaasaa}/edit', [FacaasaaController::class, 'edit'])->name('facaasaas.edit');
        Route::put('facaasaas/{facaasaa}', [FacaasaaController::class, 'update'])->name('facaasaas.update');
        Route::delete('facaasaas/{facaasaa}', [FacaasaaController::class, 'destroy'])->name('facaasaas.destroy');


        //Miisooma Beeyladaa
        Route::get('/akaakuu/beeyladaa', [ReportController::class, 'akaakuuBeeyladaa'])->name('akaakuu.baayladaa');
        Route::get('/livestock-type/{id}/plan-achievement', [ReportController::class, 'livestocktype'])->name('plan.achievement.livestock.type');
        Route::get('/livestock-plan-achievements/create', [CropPlanAchievementController::class, 'livestockcreate'])->name('livestock_plan_achievements.create');


        Route::get('/livestock', [LiveStockController::class, 'index'])->name('livestock.index');
        Route::get('/livestock/create', [LiveStockController::class, 'create'])->name('livestock.create');
        Route::post('/livestock', [LiveStockController::class, 'store'])->name('livestock.store');
        Route::get('/livestock/{id}/edit', [LiveStockController::class, 'edit'])->name('livestock.edit');
        Route::post('/livestock/{id}/update', [LiveStockController::class, 'update'])->name('livestock.update');
        Route::get('/livestock/{id}/delete', [LiveStockController::class, 'destroy'])->name('livestock.destroy');


        Route::prefix('haamaas')->name('haamaas.')->group(function () {
            Route::get('/', [HaamaaController::class, 'index'])->name('index');
            Route::get('/create', [HaamaaController::class, 'create'])->name('create');
            Route::post('/', [HaamaaController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [HaamaaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HaamaaController::class, 'update'])->name('update');
            Route::delete('/{id}', [HaamaaController::class, 'destroy'])->name('destroy');
        });


        Route::get('/yearly-crop-plans', [YearlyCropPlanController::class, 'index'])->name('yearly_crop_plans.index');
        Route::get('/yearly-crop-plans/ana', [YearlyCropPlanController::class, 'indexana'])->name('yearly_crop_plans.index.ana');
        Route::get('/yearly-crop-plans/create', [YearlyCropPlanController::class, 'create'])->name('yearly_crop_plans.create');
        Route::get('/yearly-crop-plans/create1', [YearlyCropPlanController::class, 'create1'])->name('yearly_crop_plans.create1');
        Route::get('/yearly-crop-plans/create/gana', [YearlyCropPlanController::class, 'createganaplan'])->name('yearly_crop_plans.create.gana');

        Route::post('/yearly-crop-plans', [YearlyCropPlanController::class, 'store'])->name('yearly_crop_plans.store');
        Route::get('/yearly-crop-plans/{id}/edit', [YearlyCropPlanController::class, 'edit'])->name('yearly_crop_plans.edit');
        Route::post('/yearly-crop-plans/{id}/update', [YearlyCropPlanController::class, 'update'])->name('yearly_crop_plans.update');
        Route::delete('/yearly-crop-plans/{id}', [YearlyCropPlanController::class, 'destroy'])->name('yearly_crop_plans.destroy');
        Route::get('/fetch-baseline-data', [YearlyCropPlanController::class, 'fetchBaselineData']);


        Route::get('/crops/by-type/{id}', [YearlyCropPlanController::class, 'getByType']);

        Route::get('/yearly-crop-plans/report', [YearlyCropPlanController::class, 'report'])
            ->name('yearly_crop_plans.report');


        // Route::get('/agricultural-inputs/report', [AgriculturalInputController::class, 'report'])->name('agricultural-inputs.report');

        // Route::get('/agricultural-inputs/create', [AgriculturalInputController::class, 'create'])
        //     ->name('agricultural-inputs.create');

        // Route::post('/agricultural-inputs', [AgriculturalInputController::class, 'store'])
        //     ->name('agricultural-inputs.store');

        Route::get('/village-resources/create', [VillageResourceController::class, 'create'])
            ->name('village-resources.create');

        Route::post('/village-resources', [VillageResourceController::class, 'store'])
            ->name('village-resources.store');

        Route::get('/credit-data/create', [CreditDataController::class, 'create'])->name('credit_data.create');
        Route::post('/credit-data/store', [CreditDataController::class, 'store'])->name('credit_data.store');


        Route::get('nursery-seedling/create', [NurserySeedlingController::class, 'create'])->name('nursery_seedling.create');
        Route::post('nursery-seedling/store', [NurserySeedlingController::class, 'store'])->name('nursery_seedling.store');

        Route::get('/orchard/get-plan-years', [OrchardController::class, 'getPlanYears'])->name('orchard.getPlanYears');

        Route::get('/orchard/create', [OrchardController::class, 'create'])->name('orchard.create');
        Route::post('/orchard/store', [OrchardController::class, 'store'])->name('orchard.store');

        Route::get('/productive/create', [OrchardProductionController::class, 'create'])->name('productive.create');
        Route::post('/productive/store', [OrchardProductionController::class, 'store'])->name('productive.store');

        Route::get('/livestock/supply/create', [LivestockSupplyController::class, 'create'])->name('livestock.supply.create');
        Route::post('/livestock/supply/store', [LivestockSupplyController::class, 'store'])->name('livestock.supply.store');

        Route::get('/livestock-production/create', [LivestockProductionController::class, 'create'])->name('livestock.production.create');
        Route::post('/livestock-production/store', [LivestockProductionController::class, 'store'])->name('livestock.production.store');


        Route::get('/watershed-resources/create', [WatershedResourceController::class, 'create'])->name('watershed.resources.create');
        Route::post('/watershed-resources', [WatershedResourceController::class, 'store'])->name('watershed.resources.store');

        Route::get('/forest-management/create', [ForestManagementController::class, 'create'])->name('forest.management.create');
        Route::post('/forest-management', [ForestManagementController::class, 'store'])->name('forest.management.store');


        Route::prefix('cooperative-activities')->group(function () {
            Route::get('/create', [CooperativeActivityController::class, 'create'])
                ->name('cooperative-activities.create');

            Route::post('/store', [CooperativeActivityController::class, 'store'])
                ->name('cooperative-activities.store');
        });

        Route::get('/construction-maintenance', [ConstructionMaintenanceController::class, 'create'])
            ->name('construction_maintenance.create');

        Route::post('/store', [ConstructionMaintenanceController::class, 'store'])
            ->name('construction_maintenance.store');


        Route::get('/upgrade/construction-plans/create', [UpgradingConstructionPlanController::class, 'create'])->name('new_construction_plans.create');
        Route::post('/upgrade/construction-plans', [UpgradingConstructionPlanController::class, 'store'])->name('new_construction_plans.store');

        // Route::get('/construction-plans/create', [NewConstructionPlanController::class, 'create'])->name('karora.weekly');
        // Route::post('/construction-plans', [NewConstructionPlanController::class, 'karora'])->name('karora.list');

        Route::get('/weekly/activity/create', [WeeklyTrackingController::class, 'create'])->name('construction_plans.create');
        Route::get('/weekly/activity/karora', [WeeklyTrackingController::class, 'store'])->name('construction_plans.store');
        // For the plan years dropdown
        Route::get('/orchard/get-plan-years', [OrchardController::class, 'getPlanYears'])->name('orchard.getPlanYears');
        // Weekly Plan Performance Track
        Route::get('/weekly-crop-track/create', [WeeklyTrackingController::class, 'create'])->name('weekly.crop.plan.create');


        // Route::get('weekly/activity/create', [WeeklyTrackingController::class, 'create'])->name('construction_maintenance.create');

        Route::post('weekly/activity//store', [WeeklyTrackingController::class, 'store'])->name('weekly.activity.store');


        Route::get('weekly/activity/karora', [WeeklyTrackingController::class, 'Karora'])->name('construction_maintenance.karora');
        Route::get('weekly/activity/karora/torbee/', [WeeklyTrackingController::class, 'Karoratorbee'])->name('construction_maintenance.karora.torbe');
        Route::get('weekly/activity/plan-perform-social-development/torbee/', [WeeklyTrackingController::class, 'weeklyppfParticipationContinity'])->name('construction_maintenance.karora.torbe');

        Route::get('/get-crop-types/{year}', [WeeklyTrackingController::class, 'getCropTypes']);
        Route::get('/get-crops/{year}/{cropTypeId}', [WeeklyTrackingController::class, 'getCrops']);

        Route::get('/socialdevelopment-plans/create', [SocialDevelopementController::class, 'create'])->name('social.development.create');
        Route::post('/socialdevelopment-plans/store', [SocialDevelopementController::class, 'SaveStudentParticipation'])->name('social.development_plans.store');
        //    Route::post('/socialdevelopment-plans', [SocialDevelopementController::class, 'store'])->name('social.development_plans.store');


        Route::get('/student-effectiveness/create', [SocialDevelopementController::class, 'studentEffectivenessCreate'])->name('social.student.effectiveness.create');
        Route::post('student-effectiveness/save', [SocialDevelopementController::class, 'studentEffectivenessStore'])->name('social.student.effectiveness.store');
        // Student food Supply
        Route::get('/student-food-supply/create', [SocialDevelopementController::class, 'studentFoodSupply'])->name('student.food.supply.create');
        Route::post('/student-food-supply/save', [SocialDevelopementController::class, 'studentFoodSupplyStore'])->name('student.food.supply.store');
        // For the plan years dropdown
        //Route::get('/orchard/get-plan-years', [OrchardController::class, 'getPlanYears'])->name('orchard.getPlanYears');


        //Health office
        Route::get('/health-plans/create', [HealthController::class, 'create'])->name('health.create');
        Route::post('/health-plans/store', [HealthController::class, 'store'])->name('health.store');

        Route::get('/cultural-plans/create', [CulturalRewalArtDevelopment::class, 'create'])->name('cultutal.create');
        Route::post('/cultural-plans/strore', [CulturalRewalArtDevelopment::class, 'store'])->name('cultutal.store');

        Route::get('/civil-registration-plans/create', [CivilRegistrationController::class, 'create'])->name('civil.registration.create');
        Route::post('/civil-registration-plans/save', [CivilRegistrationController::class, 'storeMultiple'])->name('civil.registration.store');
        Route::get('/civil-registration/report', [CivilRegistrationController::class, 'Report'])->name('civil_registration.report');

        Route::get('/rural-government-asset-plans/create', [RurarGovermentAssetController::class, 'create'])->name('rural.government.asset.create');
        Route::post('/rural-government-asset-plans/save', [RurarGovermentAssetController::class, 'store'])->name('rural.government.asset.store');
        Route::get('/job-opportunity-plans/create', [JobOpportunityController::class, 'create'])->name('job.opportunity.create');


        // Non-resourceful routes
        Route::get('/zonal-devisions', [ZonalDevisionController::class, 'index'])->name('zonal_devisions.index');
        Route::get('/zonal-devisions/one', [ZonalDevisionController::class, 'index1'])->name('zonal_devisions.index1');
        Route::get('/zonal-devisions/create', [ZonalDevisionController::class, 'create'])->name('zonal_devisions.create');
        Route::post('/zonal-devisions/store', [ZonalDevisionController::class, 'store'])->name('zonal_devisions.store');
        Route::get('/zonal-devisions/{id}/edit', [ZonalDevisionController::class, 'edit'])->name('zonal_devisions.edit');
        Route::post('/zonal-devisions/{id}/update', [ZonalDevisionController::class, 'update'])->name('zonal_devisions.update');
        Route::delete('/zonal-devisions/{id}', [ZonalDevisionController::class, 'destroy'])->name('zonal_devisions.destroy');

        Route::get('/fetch-crop-plan', [ZonaldevisionController::class, 'fetchCropPlan']);






        //    Route::get('/profile', [UserController::class, 'showProfile'])->name('users.profile');
        // Route::put('profile/update/{id}', [UserController::class, 'update'])->name('users.update');

        Route::get('/logout', [UserController::class, 'logout'])->name('logout');


        // Reports

        Route::get('/get-weekly-paln-reports', [ReportController::class, 'weekplanreport'])->name('karora.report');
        Route::get('/kebele/crop/report', [ReportController::class, 'Kebelereport'])->name('kebele.crop.report.');

        Route::get('/crops/by-type/{id}', [WeeklyPlanController::class, 'getByType']);
        Route::get('/yearly-crop-plan/value/{crop_id}/{plan_year}', [WeeklyPlanController::class, 'getPlanValue']);


        Route::get('/weekly-plans', [WeeklyPlanController::class, 'index'])->name('weekly-plans.index');
        Route::get('/weekly-plans/create', [WeeklyPlanController::class, 'create'])->name('weekly-plans.create');
        Route::post('/weekly-plans', [WeeklyPlanController::class, 'store'])->name('weekly-plans.store');
        Route::get('/weekly-plans/{id}/edit', [WeeklyPlanController::class, 'edit'])->name('weekly-plans.edit');
        Route::put('/weekly-plans/{id}', [WeeklyPlanController::class, 'update'])->name('weekly-plans.update');


        Route::get('/weekly-plans/land', [WeeklyLandPlanController::class, 'index'])->name('weekly-plans.land.index');


        Route::get('/weekly-plans/land/create', [WeeklyLandPlanController::class, 'create'])->name('weekly-plans.land.create');
        Route::post('/weekly-plans/land', [WeeklyLandPlanController::class, 'store'])->name('weekly-plans.land.store');
        Route::get('/weekly-plans/land/{id}/edit', [WeeklyLandPlanController::class, 'edit'])->name('weekly-plans.land.edit');
        Route::put('/weekly-plans/land/{id}', [WeeklyLandPlanController::class, 'update'])->name('weekly-plans.land.update');

        Route::get('/yearly-crop-plan/value/{crop_id}/{plan_year}', [WeeklyLandPlanController::class, 'getPlanValue']);

        Route::get('/crops/by-type/{id}', [WeeklyLandPlanController::class, 'getByType']);

        Route::get('/weekly-plans/land/ana', [WeeklyLandPlanController::class, 'indexana'])->name('weekly-plans.land.index.ana');
        Route::get('/weekly-plans/land/crop', [WeeklyLandPlanController::class, 'indexcropana'])->name('weekly-plans.land.index.zoni');


        Route::get('/karora/kurmana/ganda', [KaroorController::class, 'karooraKurmana'])->name('karora.kurmana');
        // Route::get('/karora/kurmana/ganda', [KaroorController::class, 'create'])->name('karora.create');
        Route::get('/get-crops/{cropTypeId}', [KaroorController::class, 'getCrops']);

        Route::middleware(['auth'])->group(function () {
            Route::get('/land-plans', [KaroorController::class, 'index'])->name('land-plans.index');
            Route::get('/land-plans/create', [KaroorController::class, 'create'])->name('land-plans.create');
            Route::get('/fertilizer/create', [KaroorController::class, 'fertilizer'])->name('fertilizer.create');
            Route::post('/land-plans', [KaroorController::class, 'store'])->name('land-plans.store');
            Route::get('/land-plans/{id}/edit', [KaroorController::class, 'edit'])->name('land-plans.edit');
            Route::delete('/land-plans/{id}', [KaroorController::class, 'destroy'])->name('land-plans.destroy');

            Route::get('/reports/land-farming', [KaroorController::class, 'generateReport'])
                ->name('reports.landFarming');

            Route::get('/reports/ganna-season-crops', [KaroorController::class, 'gannaSeasonCrops'])
                ->name('reports.ganna-season-crops');

            Route::get('/reports/bona-season-crops', [KaroorController::class, 'bonaSeasonCrops'])
                ->name('reports.bona-season-crops');
            Route::get('/reports/arfasa-season-crops', [KaroorController::class, 'arfasaSeasonCrops'])
                ->name('reports.arfasa-season-crops');

            Route::get('/reports/kebele-crop-analysis', [KaroorController::class, 'kebeleCropAnalysis'])
                ->name('reports.kebele-crop-analysis')
                ->middleware('auth');




            Route::get('/reports/woreda-crop-analysis', [KaroorController::class, 'woredaCropAnalysis'])
                ->name('reports.woreda-crop-analysis')
                ->middleware('auth');

            Route::get('/reports/zone-crop-analysis', [KaroorController::class, 'zoneCropAnalysis'])
                ->name('reports.zone-crop-analysis')
                ->middleware('auth');

            Route::get('/reports/kebele-livestock-analysis', [ReportController::class, 'kebeleLivestockAnalysis'])->name('reports.kebele-livestock-analysis');
            Route::get('/reports/woreda-livestock-analysis', [ReportController::class, 'woredaLivestockAnalysis'])->name('reports.woreda-livestock-analysis');
            Route::get('/reports', [ReportController::class, 'allreport'])->name('all.reports');


            Route::get('/agricultural_inputs/create/latest', [AgriculturalInputController::class, 'createlatest'])->name('inputs.create');

            Route::post('/agricultural-inputs/latest', [AgriculturalInputController::class, 'storelatest'])->name('inputs.store');
            Route::get('/inputs', [AgriculturalInputController::class, 'index'])->name('inputs.index');
            Route::get('/kebele-admin/report', [AgriculturalInputController::class, 'kebeleReport'])->name('kebele.report');
            Route::get('/woreda-admin/report', [AgriculturalInputController::class, 'woredaReport'])
                ->middleware('auth')
                ->name('woreda.report');

            Route::get('/zone-admin/report', [AgriculturalInputController::class, 'zoneReport'])
                ->middleware('auth')
                ->name('zone.report');

            Route::get('/inputs/export', [AgriculturalInputController::class, 'export'])->name('inputs.export');
        });


        //Route::get('/liqii/qusannaa/create', [LoanController::class, 'create'])->name('liqii.qusannaa.create');
        //Route::post('/liqii/qusannaa', [LoanController::class, 'store'])->name('liqii.qusannaa.store');
        //=======

        Route::get('/liqii/qusannaa/create', [LoanController::class, 'create'])->name('liqii.qusannaa.create');
        Route::post('/liqii/qusannaa', [LoanController::class, 'store'])->name('liqii.qusannaa.store');
        Route::get('/kebele/report/mech', [LoanController::class, 'kebeleReport'])->name('kebele.mech.report');
        Route::get('/woreda/report/mech', [LoanController::class, 'woredaReport'])->name('woreda.mech.report');
        Route::get('/zone/report/mech', [LoanController::class, 'zoneReport'])->name('zone.mech.report');
        Route::post('/kebele/report/mechi', [LoanController::class, 'generate'])->name('kebele.report.generate');

        Route::get('/agricultural_inputs/create/latest', [AgriculturalInputController::class, 'createlatest'])->name('inputs.create');
        Route::post('/agricultural-inputs/latest', [AgriculturalInputController::class, 'storelatest'])->name('inputs.store');
        Route::get('/inputs', [AgriculturalInputController::class, 'index'])->name('inputs.index');
        //Kebele Dashboard
        Route::get('/kebele-dashboard', [DashboardController::class, 'kebeleDashboard'])->name('kebele.dashboard');


        // Structure route


        Route::get('/agricultural_inputs/create/latest', [AgriculturalInputController::class, 'createlatest'])->name('inputs.create');

        Route::post('/agricultural-inputs/latest', [AgriculturalInputController::class, 'storelatest'])->name('inputs.store');
        Route::get('/inputs', [AgriculturalInputController::class, 'index'])->name('inputs.index');
        Route::get('/kebele-admin/report', [AgriculturalInputController::class, 'kebeleReport'])->name('kebele.report');
        Route::get('/woreda-admin/report', [AgriculturalInputController::class, 'woredaReport'])
            ->middleware('auth')
            ->name('woreda.report');

        Route::get('/zone-admin/report', [AgriculturalInputController::class, 'zoneReport'])
            ->middleware('auth')
            ->name('zone.report');

        Route::get('/inputs/export', [AgriculturalInputController::class, 'export'])->name('inputs.export');


        Route::get('/dhiyeessii/mechenization', [MaterialSuplyController::class, 'create'])->name('dhiyessi.create');
        Route::post('/material-supplies', [MaterialSuplyController::class, 'store'])
            ->name('material-supplies.store');
        Route::get('/material-supplies/report', [MaterialSuplyController::class, 'kebelereport'])->name('supplies.report');


        Route::get('/liqii/qusannaa', [LoanController::class, 'create'])->name('liqii.create');
        Route::post('/material-supplies', [LoanController::class, 'store'])
            ->name('liqii.store');

        Route::get('/agricultural_inputs/create/latest', [AgriculturalInputController::class, 'createlatest'])->name('inputs.create');
        Route::post('/agricultural-inputs/latest', [AgriculturalInputController::class, 'storelatest'])->name('inputs.store');
        Route::get('/inputs', [AgriculturalInputController::class, 'index'])->name('inputs.index');
        //Kebele Dashboard
        Route::get('/kebele-dashboard', [DashboardController::class, 'kebeleDashboard'])->name('kebele.dashboard');
        // Route::get('/zone-dashboard', [DashboardController::class, 'zoneDashboard'])->name('zone.dashboard');

        // Structure route


        // Structure route
        Route::get('croptype', [StructureController::class, 'croptypelist'])->name('croptype.index');
        Route::post('croptype/save', [StructureController::class, 'storecroptype'])->name('croptype.store');


        Route::get('crops', [StructureController::class, 'croplist'])->name('crops.index');
        Route::post('crops/save', [StructureController::class, 'storecrop'])->name('crops.store');


        Route::get('/monthly-plans/create', [MonthlyPlanController::class, 'create'])->name('monthly-plans.create');
        Route::post('/monthly-plans', [MonthlyPlanController::class, 'store'])->name('monthly-plans.store');


        Route::get('/api/yearly-plans', [MonthlyPlanController::class, 'getFilteredPlans'])->name('api.yearly-plans');


        ///crop type

        Route::get('/compost-types', [CompostTypeController::class, 'index'])->name('compost-types.index');
        Route::get('/compost-types/create', [CompostTypeController::class, 'create'])->name('compost-types.create');
        Route::post('/compost-types', [CompostTypeController::class, 'store'])->name('compost-types.store');
        Route::get('/compost-types/{compostType}', [CompostTypeController::class, 'show'])->name('compost-types.show');
        Route::get('/compost-types/{compostType}/edit', [CompostTypeController::class, 'edit'])->name('compost-types.edit');
        Route::put('/compost-types/{compostType}', [CompostTypeController::class, 'update'])->name('compost-types.update');
        Route::delete('/compost-types/{compostType}', [CompostTypeController::class, 'destroy'])->name('compost-types.destroy');

        //gabbina biyyoo


        Route::get('/gabbina-biyyee/create', [GabbinaBiyyeeController::class, 'create'])->name('gabbina_biyyee.create');
        Route::post('/gabbina-biyyee/store', [GabbinaBiyyeeController::class, 'store'])->name('gabbina_biyyee.store');

        Route::get('/compost-report', [GabbinaBiyyeeController::class, 'index'])->name('compost.report');
        Route::get('/woreda/compost-report', [GabbinaBiyyeeController::class, 'woreda'])->name('compost.report.woreda');


        Route::get('/dhiyeessii/mechenization', [MaterialSuplyController::class, 'create'])->name('mechinization.create');
        Route::post('/material-supplies', [MaterialSuplyController::class, 'store'])
            ->name('material-suppl.store');

        Route::get('/material-supplies/report', [MaterialSuplyController::class, 'kebelereport'])->name('kebelereport');


        Route::get('seedling-types', [SeedlingTypeController::class, 'index'])->name('seedling-types.index');
        Route::get('seedling-types/create', [SeedlingTypeController::class, 'create'])->name('seedling-types.create');
        Route::post('seedling-types', [SeedlingTypeController::class, 'store'])->name('seedling-types.store');
        Route::get('seedling-types/{id}/edit', [SeedlingTypeController::class, 'edit'])->name('seedling-types.edit');
        Route::put('seedling-types/{id}', [SeedlingTypeController::class, 'update'])->name('seedling-types.update');
        Route::delete('seedling-types/{id}', [SeedlingTypeController::class, 'destroy'])->name('seedling-types.destroy');

        Route::get('seedlings', [SeedlingTypeController::class, 'indexs'])->name('seedlings.index');
        Route::get('seedlings/create', [SeedlingTypeController::class, 'creates'])->name('seedlings.create');
        Route::post('seedlings', [SeedlingTypeController::class, 'stores'])->name('seedlings.store');
        Route::get('seedlings/{id}/edit', [SeedlingTypeController::class, 'edits'])->name('seedlings.edit');
        Route::put('seedlings/{id}', [SeedlingTypeController::class, 'updates'])->name('seedlings.update');
        Route::delete('seedlings/{id}', [SeedlingTypeController::class, 'destroys'])->name('seedlings.destroy');

        Route::get('/nursery-sites/create', [NurserySiteController::class, 'create'])->name('nursery-sites.create');

        Route::post('/nursery-sites', [NurserySiteController::class, 'store'])->name('nursery-sites.store');

        Route::get('/seedling-preparations/create', [SeedlingPreparationController::class, 'create'])->name('seedling-preparations.create');
        Route::post('/seedling-preparations', [SeedlingPreparationController::class, 'store'])->name('seedling-preparations.store');

        Route::get('/seedling-production/create', [SeedlingPreparationController::class, 'createp'])->name('seedling-production.create');
        Route::post('/seedling-production', [SeedlingPreparationController::class, 'storep'])->name('seedling-production.store');


        // Construction Of Institutions route
        Route::get('/construction-institution-plans/create', [ConstructionController::class, 'create'])->name('construction.institute.create');
        Route::post('/construction-plans/store', [ConstructionController::class, 'store'])->name('construction.store');
        Route::get('/construction-report', [ConstructionController::class, 'report'])->name('construction.report');
        //Fooyaa'insaa dhaabbilee
        Route::get('/institution-improvement-plans/create', [InstitutionImprovementController::class, 'create'])->name('improvement.institute.create');
        Route::post('/improvement-plans/store', [InstitutionImprovementController::class, 'store'])->name('infrastructure.store');
        Route::get('/improvement-report', [InstitutionImprovementController::class, 'report'])->name('infrastructure.report');


        Route::get('/energy-plan', [FacilityController::class, 'create'])->name('energy.create');
        Route::post('/energy-plan', [FacilityController::class, 'store'])->name('energy.store');
        Route::get('/energy-plan/report', [FacilityController::class, 'report'])->name('energy_plan.report');

        Route::get('/economic-create', [EconomicTransitionController::class, 'create'])->name('economic_facility.create');
        Route::post('/economic-store', [EconomicTransitionController::class, 'store'])->name('economic_facility.store');
        Route::get('/economic-report', [EconomicTransitionController::class, 'report'])->name('economic_facility.report');

        Route::get('/seedlings/by-type/{seedlingtype}', [SeedlingPreparationController::class, 'getSeedlingsByType'])->name('seedlings.byType');


        Route::get('/misoma-beyladas/create', [MisomaBeyladaController::class, 'create'])->name('misoma_beyladas.create');
        Route::post('/misoma-beyladas', [MisomaBeyladaController::class, 'store'])->name('misoma_beyladas.store');


        Route::get('/misoma-beyladas-production/create', [MisomaBeyladaController::class, 'createp'])->name('misoma_beyladas_production.create');
        Route::post('/misoma-beyladas-production', [MisomaBeyladaController::class, 'storep'])->name('misoma_beyladas_production.store');


        Route::get('/qabenya/create', [QabenyaUmamaController::class, 'createp'])->name('qabenya.create');
        Route::post('/qabenya', [QabenyaUmamaController::class, 'storep'])->name('qabenya.store');


        Route::get('/job-records/create', [JobCreationController::class, 'create'])->name('job-records.create');
        Route::post('/job-records', [JobCreationController::class, 'store'])->name('job-records.store');
        Route::get('/reports/job', [JobCreationController::class, 'jobReport'])->name('reports.job_report');
        Route::get('/woreda/reports/job', [JobCreationController::class, 'jobReportworeda'])->name('woreda.reports.job_report');
        // Route::get('/zone/reports/job', [JobCreationController::class, 'jobReportzone'])->name('zone.reports.job_report');
        Route::get('/zone/reports/job', [JobCreationController::class, 'jobReportZone'])
            ->name('zone.reports.job_report')
            ->middleware(['auth']);

        Route::get('/associations/create', [AssociationController::class, 'create'])->name('babalin.associations.create');
        Route::post('/associations', [AssociationController::class, 'store'])->name('babalin.associations.store');

        Route::get('/egumsa/create', [EggumsaNaannooController::class, 'create'])->name('egumsa.associations.create');
        Route::post('/egumsa', [EggumsaNaannooController::class, 'store'])->name('egumsa.associations.store');
    });

   
    Route::middleware(['auth', 'role:5'])->prefix('sector')->name('sector.')->group(function () {
        Route::get('crop-plans', [KebeleCropPlanController::class, 'index'])->name('crop-plans.index');
        // In routes/web.php
        Route::get('/dashboard/sector', [DashboardController::class, 'sectordashboard'])->name('sectoral.dashboard');

        Route::get('crop-plans/{id}/edit', [KebeleCropPlanController::class, 'edit'])->name('crop-plans.edit');
        Route::put('crop-plans/{id}', [KebeleCropPlanController::class, 'update'])->name('crop-plans.update');
        Route::delete('crop-plans/{id}', [KebeleCropPlanController::class, 'destroy'])->name('crop-plans.destroy');
        Route::get('crop-plans/create', [KebeleCropPlanController::class, 'create'])->name('crop-plans.create');
        Route::post('crop-plans/store', [KebeleCropPlanController::class, 'store'])->name('crop-plans.store');
        Route::get('/crops-by-type/{croptype}', [KebeleCropPlanController::class, 'getCropsByType'])->name('sector.crops.byType');

        Route::get('economic-plans', [KebeleEconomicPlanController::class, 'index'])->name('sector.economic.index');
        Route::get('economic-plans/create', [KebeleEconomicPlanController::class, 'create'])->name('sector.economic.create');
        Route::post('economic-plans/store', [KebeleEconomicPlanController::class, 'store'])->name('sector.economic.store');
        Route::get('economic-plans/{id}/edit', [KebeleEconomicPlanController::class, 'edit'])->name('sector.economic.edit');
        Route::put('economic-plans/{id}/update', [KebeleEconomicPlanController::class, 'update'])->name('sector.economic.update');
        Route::delete('economic-plans/{id}/delete', [KebeleEconomicPlanController::class, 'destroy'])->name('sector.economic.destroy');

        // setting  
       
    });
    


    // Kebele Admin only
    Route::middleware('role:4')->prefix('kebele')->group(function () {
        Route::get('/woreda/users', [UserController::class, 'woredaUsers'])->name('woreda.users');
        // add more woreda admin routes here
        Route::get('profile/{uuid}/edit', [UserController::class, 'edit'])->name('woreda.users.edit');
        Route::put('profile/{uuid}', [UserController::class, 'update'])->name('woreda.users.update');
        Route::get('/profile/users/{uuid}', [UserController::class, 'edit'])->name('woreda.users.edit');

        Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('users.profile');
        Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('users.password');
        Route::post('/change-password', [UserController::class, 'changePassword'])->name('password.change');


        Route::get('profile/{uuid}/edit', [UserController::class, 'edit'])->name('kebele.users.edit');
        Route::put('profile/{uuid}', [UserController::class, 'profileupdate'])->name('kebele.users.update');
        // Route::put('profile/{uuid}', [UserController::class, 'update'])->name('kebele.users.update');
        Route::get('/profile/users/{uuid}', [UserController::class, 'profileedit'])->name('kebele.users.edit');

        Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('kebele.profile.users');
        Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('kebele.users.password');
        Route::post('/change-password', [UserController::class, 'changePassword'])->name('kebele.password.change');
    });
    Route::middleware('role:4')->prefix('kebele')->group(function () {
        Route::post('sectors/store', [StructureController::class, 'kebelesectorstore'])->name('sectors.store');
        Route::get('sectors/create', [StructureController::class, 'sectorcreate'])->name('sectors.create');
        Route::get('sectors', [StructureController::class, 'sektorsIndex'])->name('sectors.index');
        Route::delete('sectors/{id}', [StructureController::class, 'destroySector'])->name('sectors.destroy');

        Route::get('sector-admins', [StructureController::class, 'indexSectorAdmins'])->name('sectoradmin.index');
        Route::get('sector-admin/create', [StructureController::class, 'createSectorAdmin'])->name('sectoradmin.create');
        Route::post('sector-admin/store', [StructureController::class, 'storeSectorAdmin'])->name('sectoradmin.store');
        Route::get('sector-admin/edit/{id}', [StructureController::class, 'editSectorAdmin'])->name('sectoradmin.edit');
        Route::put('sector-admin/update/{id}', [StructureController::class, 'updateSectorAdmin'])->name('sectoradmin.update');
        Route::delete('sector-admin/delete/{id}', [StructureController::class, 'destroySectorAdmin'])->name('sectoradmin.destroy');


        Route::get('/dashboard', [KebeleAdminController::class, 'dashboard'])->name('kebele.dashboard');
        Route::post('/approve/{sector}/{id}', [KebeleAdminController::class, 'approve'])->name('kebele.approve');
        Route::post('/reject/{sector}/{id}', [KebeleAdminController::class, 'reject'])->name('kebele.reject');
        Route::post('update-status', [KebeleAdminController::class, 'updateStatus'])->name('kebele.update.status');
    });
});

// route/web.php 

Route::get('/federations', [FederationController::class, 'index'])->name('federations.index');
Route::get('/federations/create', [FederationController::class, 'create'])->name('federations.create');
Route::post('/federations/store', [FederationController::class, 'store'])->name('federations.store');
Route::get('/federations/{id}', [FederationController::class, 'show'])->name('federations.show');
Route::get('/federations/{id}/edit', [FederationController::class, 'edit'])->name('federations.edit');
Route::put('/federations/{id}/update', [FederationController::class, 'update'])->name('federations.update');
Route::delete('/federations/{id}/delete', [FederationController::class, 'destroy'])->name('federations.destroy');




Route::get('/unions', [UnionController::class, 'index'])->name('unions.index');
Route::get('/unions/create', [UnionController::class, 'create'])->name('unions.create');
Route::post('/unions/store', [UnionController::class, 'store'])->name('unions.store');
Route::get('/unions/{id}/edit', [UnionController::class, 'edit'])->name('unions.edit');
Route::post('/unions/{id}/update', [UnionController::class, 'update'])->name('unions.update');
Route::get('/unions/{id}/show', [UnionController::class, 'show'])->name('unions.show');
Route::delete('/unions/{id}', [UnionController::class, 'destroy'])->name('unions.destroy');


Route::get('/associations', [AssociationController::class, 'index'])->name('associations.index');
Route::get('/associations/create', [AssociationController::class, 'create'])->name('associations.create');
Route::post('/associations/store', [AssociationController::class, 'store'])->name('associations.store');
Route::get('/associations/edit/{id}', [AssociationController::class, 'edit'])->name('associations.edit');
Route::post('/associations/update/{id}', [AssociationController::class, 'update'])->name('associations.update');
Route::delete('/associations/destroy/{id}', [AssociationController::class, 'destroy'])->name('associations.destroy');
Route::get('/associations/show/{id}', [AssociationController::class, 'show'])->name('associations.show');

Route::get('/associations/get-unions/{federation}', [AssociationController::class, 'getUnions']);
Route::get('/associations/get-woredas/{zone}', [AssociationController::class, 'getWoredas']);
Route::get('/associations/get-kebeles/{woreda}', [AssociationController::class, 'getKebeles']);
Route::get('/associations/filter', [AssociationController::class, 'filter'])->name('associations.filter');


Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
Route::get('/farmers/create', [FarmerController::class, 'create'])->name('farmers.create');
Route::post('/farmers/store', [FarmerController::class, 'store'])->name('farmers.store');
Route::get('/farmers/edit/{id}', [FarmerController::class, 'edit'])->name('farmers.edit');
Route::post('/farmers/update/{id}', [FarmerController::class, 'update'])->name('farmers.update');
Route::delete('/farmers/destroy/{id}', [FarmerController::class, 'destroy'])->name('farmers.destroy');
Route::get('/farmers/show/{id}', [FarmerController::class, 'show'])->name('farmers.show');


// Route::get('/weekly-plans', [WeeklyPlanController::class, 'index'])->name('weekly-plans.index');
// Route::get('/weekly-plans/create', [WeeklyPlanController::class, 'create'])->name('weekly-plans.create');
// Route::post('/weekly-plans', [WeeklyPlanController::class, 'store'])->name('weekly-plans.store');