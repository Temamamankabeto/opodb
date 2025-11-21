<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Controllers\UnionController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\KaroorController;
use App\Http\Controllers\SoilTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\WeeklyPlanController;
use App\Http\Controllers\AssociationController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\JobCreationController;
use App\Http\Controllers\KebeleAdminController;
use App\Http\Controllers\GabbinaBiyyeeController;
use App\Http\Controllers\LivestockPlanController;
use App\Http\Controllers\MechanizationController;
use App\Http\Controllers\EnterpriseWorkController;
use App\Http\Controllers\FertilizerTypeController;
use App\Http\Controllers\JobOpportunityController;
use App\Http\Controllers\KebeleCropPlanController;
use App\Http\Controllers\ParcelCropPlanController;
use App\Http\Controllers\WeeklyLandPlanController;
use App\Http\Controllers\AssociationTypeController;
use App\Http\Controllers\CulturalRevivalController;
use App\Http\Controllers\LivestockSupplyController;
use App\Http\Controllers\NaturalResourceController;
use App\Http\Controllers\LivestockProductController;
use App\Http\Controllers\Setting\LanguageController;
use App\Http\Controllers\CropConfigurationController;
use App\Http\Controllers\StudentMealSupplyController;
use App\Http\Controllers\KebeleEconomicPlanController;
use App\Http\Controllers\NaturalResourcePlanController;
use App\Http\Controllers\SeedlingPreparationController;
use App\Http\Controllers\AssociationExpansionPlanController;
use App\Http\Controllers\EducationalEffectivenessController;
use App\Http\Controllers\EducationalParticipationController;
use App\Http\Controllers\KebeleGovernmentPropertyController;
use App\Http\Controllers\EmploymentOpportunityPlanController;
use App\Http\Controllers\EnvironmentalProtectionPlanController;
use App\Http\Controllers\BuildingPowerInstitutionPlanController;
use App\Http\Controllers\RegistrationOfCivilCertificateController;
use App\Http\Controllers\BuildingEconomicInstitutionPlanController;
use App\Http\Controllers\BuildingCommunityInstitutionPlanController;

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
 Route::get('crop-plans', [DashboardController::class, 'indexregioncrop'])->name('crop-plans.index');
            Route::post('sectors/store', [StructureController::class, 'kebelesectorstore'])->name('sectors.store');
            Route::get('sectors/create', [StructureController::class, 'sectorcreate'])->name('sectors.create');
            Route::get('sectors', [StructureController::class, 'sektorsIndex'])->name('sectors.index');
            Route::get('sectors/{id}/edit', [StructureController::class, 'editSector'])->name('sectors.edit');
            Route::get('sectors/{id}/product', [StructureController::class, 'editSector'])->name('sectors.edit');
            Route::put('sectors/{id}', [StructureController::class, 'updateSector'])->name('sectors.update');
            Route::delete('sectors/{id}', [StructureController::class, 'destroySector'])->name('sectors.destroy');

        });

        Route::middleware('role:1,2')->prefix('admin')->group(function () {

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

            Route::get('/livestock-supplies', [LivestockSupplyController::class, 'index'])->name('livestock_supplies.index');
            Route::get('/livestock-supplies/create', [LivestockSupplyController::class, 'create'])->name('livestock_supplies.create');
            Route::post('/livestock-supplies/store', [LivestockSupplyController::class, 'store'])->name('livestock_supplies.store');
            Route::get('/livestock-supplies/edit/{id}', [LivestockSupplyController::class, 'edit'])->name('livestock_supplies.edit');
            Route::post('/livestock-supplies/update/{id}', [LivestockSupplyController::class, 'update'])->name('livestock_supplies.update');
            Route::get('/livestock-supplies/delete/{id}', [LivestockSupplyController::class, 'destroy'])->name('livestock_supplies.delete');

            Route::get('/livestock_product', [LivestockProductController::class, 'index'])->name('livestock_product.index');
            Route::get('/livestock_product/create', [LivestockProductController::class, 'create'])->name('livestock_product.create');
            Route::post('/livestock_product/store', [LivestockProductController::class, 'store'])->name('livestock_product.store');
            Route::get('/livestock_product/edit/{id}', [LivestockProductController::class, 'edit'])->name('livestock_product.edit');
            Route::post('/livestock_product/update/{id}', [LivestockProductController::class, 'update'])->name('livestock_product.update');
            Route::get('/livestock_product/delete/{id}', [LivestockProductController::class, 'destroy'])->name('livestock_product.delete');

            Route::get('/naturals', [NaturalResourceController::class, 'index'])->name('naturals.index');
            Route::get('/naturals/create', [NaturalResourceController::class, 'create'])->name('naturals.create');
            Route::post('/naturals/store', [NaturalResourceController::class, 'store'])->name('naturals.store');
            Route::get('/naturals/edit/{id}', [NaturalResourceController::class, 'edit'])->name('naturals.edit');
            Route::post('/naturals/update/{id}', [NaturalResourceController::class, 'update'])->name('natural.update');
            Route::get('/naturals/delete/{id}', [NaturalResourceController::class, 'destroy'])->name('naturals.delete');

            Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
Route::get('/institutions/create', [InstitutionController::class, 'create'])->name('institutions.create');
Route::post('/institutions/store', [InstitutionController::class, 'store'])->name('institutions.store');
Route::get('/institutions/edit/{id}', [InstitutionController::class, 'edit'])->name('institutions.edit');
Route::put('/institutions/update/{id}', [InstitutionController::class, 'update'])->name('institutions.update');
Route::delete('/institutions/delete/{id}', [InstitutionController::class, 'destroy'])->name('institutions.destroy');

Route::get('/educational-participations', [EducationalParticipationController::class, 'index'])->name('educational_participations.index');
Route::get('/educational-participations/create', [EducationalParticipationController::class, 'create'])->name('educational_participations.create');
Route::post('/educational-participations/store', [EducationalParticipationController::class, 'store'])->name('educational_participations.store');
Route::get('/educational-participations/edit/{id}', [EducationalParticipationController::class, 'edit'])->name('educational_participations.edit');
Route::put('/educational-participations/update/{id}', [EducationalParticipationController::class, 'update'])->name('educational_participations.update');
Route::delete('/educational-participations/delete/{id}', [EducationalParticipationController::class, 'destroy'])->name('educational_participations.destroy');

    Route::get('/educational-effectiveness', [EducationalEffectivenessController::class, 'index'])->name('educational_effectiveness.index');
    Route::get('/educational-effectiveness/create', [EducationalEffectivenessController::class, 'create'])->name('educational_effectiveness.create');
    Route::post('/educational-effectiveness/store', [EducationalEffectivenessController::class, 'store'])->name('educational_effectiveness.store');
    Route::get('/educational-effectiveness/edit/{id}', [EducationalEffectivenessController::class, 'edit'])->name('educational_effectiveness.edit');
    Route::put('/educational-effectiveness/update/{id}', [EducationalEffectivenessController::class, 'update'])->name('educational_effectiveness.update');
    Route::delete('/educational-effectiveness/delete/{id}', [EducationalEffectivenessController::class, 'destroy'])->name('educational_effectiveness.delete');

     Route::get('/student-meal-supplies', [StudentMealSupplyController::class, 'index'])->name('student_meal_supply.index');
    Route::get('/student-meal-supplies/create', [StudentMealSupplyController::class, 'create'])->name('student_meal_supply.create');
    Route::post('/student-meal-supplies/store', [StudentMealSupplyController::class, 'store'])->name('student_meal_supply.store');
    Route::get('/student-meal-supplies/edit/{id}', [StudentMealSupplyController::class, 'edit'])->name('student_meal_supply.edit');
    Route::put('/student-meal-supplies/update/{id}', [StudentMealSupplyController::class, 'update'])->name('student_meal_supply.update');
    Route::delete('/student-meal-supplies/delete/{id}', [StudentMealSupplyController::class, 'destroy'])->name('student_meal_supply.delete');

      Route::get('/health', [HealthController::class, 'index'])->name('health.index');
    Route::get('/health/create', [HealthController::class, 'create'])->name('health.create');
    Route::post('/health/store', [HealthController::class, 'store'])->name('health.store');
    Route::get('/health/edit/{id}', [HealthController::class, 'edit'])->name('health.edit');
    Route::put('/health/update/{id}', [HealthController::class, 'update'])->name('health.update');
    Route::put('/healthupdate/{id}', [HealthController::class, 'update'])->name('health.update');
    Route::delete('/health/delete/{id}', [HealthController::class, 'destroy'])->name('health.delete');

     Route::get('/cultural-revival', [CulturalRevivalController::class, 'index'])->name('cultural_revival.index');
    Route::get('/cultural-revival/create', [CulturalRevivalController::class, 'create'])->name('cultural_revival.create');
    Route::post('/cultural-revival/store', [CulturalRevivalController::class, 'store'])->name('cultural_revival.store');
    Route::get('/cultural-revival/edit/{id}', [CulturalRevivalController::class, 'edit'])->name('cultural_revival.edit');
    Route::put('/cultural-revival/update/{id}', [CulturalRevivalController::class, 'update'])->name('cultural_revival.update');
    Route::delete('/cultural-revival/delete/{id}', [CulturalRevivalController::class, 'destroy'])->name('cultural_revival.delete');


    Route::get('/civil-certificates', [RegistrationOfCivilCertificateController::class, 'index'])->name('civil.index');
Route::get('/civil-certificates/create', [RegistrationOfCivilCertificateController::class, 'create'])->name('civil.create');
Route::post('/civil-certificates', [RegistrationOfCivilCertificateController::class, 'store'])->name('civil.store');

Route::get('/civil-certificates/{id}/edit', [RegistrationOfCivilCertificateController::class, 'edit'])->name('civil.edit');
Route::post('/civil-certificates/{id}', [RegistrationOfCivilCertificateController::class, 'update'])->name('civil.update');

Route::delete('/civil-certificates/{id}', [RegistrationOfCivilCertificateController::class, 'destroy'])->name('civil.destroy');

Route::get('/government-properties', [KebeleGovernmentPropertyController::class, 'index'])->name('govprop.index');
Route::get('/government-properties/create', [KebeleGovernmentPropertyController::class, 'create'])->name('govprop.create');
Route::post('/government-properties', [KebeleGovernmentPropertyController::class, 'store'])->name('govprop.store');

Route::get('/government-properties/{id}/edit', [KebeleGovernmentPropertyController::class, 'edit'])->name('govprop.edit');
Route::post('/government-properties/{id}', [KebeleGovernmentPropertyController::class, 'update'])->name('govprop.update');

Route::delete('/government-properties/{id}', [KebeleGovernmentPropertyController::class, 'destroy'])->name('govprop.destroy');

Route::get('/job-opportunities', [JobOpportunityController::class, 'index'])->name('job.index');
Route::get('/job-opportunities/create', [JobOpportunityController::class, 'create'])->name('job.create');
Route::post('/job-opportunities', [JobOpportunityController::class, 'store'])->name('job.store');

Route::get('/job-opportunities/{id}/edit', [JobOpportunityController::class, 'edit'])->name('job.edit');
Route::post('/job-opportunities/{id}', [JobOpportunityController::class, 'update'])->name('job.update');

Route::delete('/job-opportunities/{id}', [JobOpportunityController::class, 'destroy'])->name('job.destroy');


Route::get('/enterprise-work', [EnterpriseWorkController::class, 'index'])->name('enterprise.index');
Route::get('/enterprise-work/create', [EnterpriseWorkController::class, 'create'])->name('enterprise.create');
Route::post('/enterprise-work', [EnterpriseWorkController::class, 'store'])->name('enterprise.store');

Route::get('/enterprise-work/{id}/edit', [EnterpriseWorkController::class, 'edit'])->name('enterprise.edit');
Route::post('/enterprise-work/{id}', [EnterpriseWorkController::class, 'update'])->name('enterprise.update');

Route::delete('/enterprise-work/{id}', [EnterpriseWorkController::class, 'destroy'])->name('enterprise.destroy');

        });

        // Zone Admin only
        Route::middleware('role:2,4')->prefix('zones')->group(function () {
        Route::get('/crop-plans', [DashboardController::class, 'indexzonecrop'])->name('crop-plans.index');
        Route::get('/report/crop-plans', [DashboardController::class, 'indexzonecrop'])->name('crop-plans.index');
            // Route::get('users', [UserController::class, 'zoneUsers'])->name('zone.users');
            Route::get('zone-list', [ZoneController::class, 'zoneOnlyList'])->name('zone.zones');
            Route::get('all-zones', [StructureController::class, 'zonelist'])->name('zones.index');
            Route::post('zones/save', [StructureController::class, 'storezone'])->name('zones.store');

            Route::get('woreda/users', [UserController::class, 'woredaUsers'])->name('woreda.users');
            Route::get('dashboard', [DashboardController::class, 'zoneDashboard'])->name('zone.dashboard');

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
            Route::get('woreda/crop-plans/report', [DashboardController::class, 'indexcrop'])->name('crop-plans.index');

            Route::get('woreda/crop-plan/feedback/{$id}', [DashboardController::class, 'indexcrop'])->name('woreda.crop-plans.feedback');



            Route::post('/crop-plans/{id}/feedback', [DashboardController::class, 'storeFeedback']);




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

            Route::get('sector-admins', [StructureController::class, 'indexSectorAdmins'])->name('sectoradmin.index');
            Route::get('users/sector-user', [UserController::class, 'sectoruser'])->name('sector.users.index');
            Route::get('sector-admin/create', [StructureController::class, 'createSectorAdmin'])->name('sectoradmin.create');
            Route::post('sector-admin/store', [StructureController::class, 'storeSectorAdmin'])->name('sectoradmin.store');
            Route::get('sector-admin/edit/{id}', [StructureController::class, 'editSectorAdmin'])->name('sectoradmin.edit');
            Route::put('sector-admin/update/{id}', [StructureController::class, 'updateSectorAdmin'])->name('sectoradmin.update');
            Route::delete('sector-admin/delete/{id}', [StructureController::class, 'destroySectorAdmin'])->name('sectoradmin.destroy');

            Route::get('/dashboard', [KebeleAdminController::class, 'dashboard'])->name('kebele.dashboard');
            Route::post('/approve/{sector}/{id}', [KebeleAdminController::class, 'approve'])->name('kebele.approve');
            Route::post('/reject/{sector}/{id}', [KebeleAdminController::class, 'reject'])->name('kebele.reject');
            Route::post('update-status', [KebeleAdminController::class, 'updateStatus'])->name('kebele.update.status');

            Route::get('da-users', [StructureController::class, 'indexdausers'])->name('dausers.index');
            Route::get('da-user/create', [StructureController::class, 'createdausers'])->name('dausers.create');
            Route::post('da-user/store', [StructureController::class, 'storedausers'])->name('dausers.store');
            Route::get('da-user/edit/{id}', [StructureController::class, 'editdausers'])->name('dausers.edit');
            Route::put('da-user/update/{id}', [StructureController::class, 'updatedausers'])->name('dausers.update');
            Route::delete('da-user/delete/{id}', [StructureController::class, 'destroydausers'])->name('dausers.destroy');

            Route::get('/crop-configurations', [CropConfigurationController::class, 'index'])->name('crop-configurations.index');
            Route::get('/crop-configurations/create', [CropConfigurationController::class, 'create'])->name('crop-configurations.create');
            Route::post('/crop-configurations', [CropConfigurationController::class, 'store'])->name('crop-configurations.store');
            Route::get('/crop-configurations/{id}/edit', [CropConfigurationController::class, 'edit'])->name('crop-configurations.edit');
            Route::put('/crop-configurations/{id}', [CropConfigurationController::class, 'update'])->name('crop-configurations.update');
            Route::delete('/crop-configurations/{id}', [CropConfigurationController::class, 'destroy'])->name('crop-configurations.destroy');
            Route::get('/crop-configurations/get-crops-by-type/{croptype_id}', [CropConfigurationController::class, 'getCropsByType']);

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

           // AJAX Routes for cascading dropdowns
            Route::get('/associations/get-unions/{federation_id}', [AssociationController::class, 'getUnions'])->name('associations.get-unions');
            Route::get('/associations/get-woredas/{zone_id}', [AssociationController::class, 'getWoredas'])->name('associations.get-woredas');
            Route::get('/associations/get-kebeles/{woreda_id}', [AssociationController::class, 'getKebeles'])->name('associations.get-kebeles');
            Route::get('/associations/get-services', [AssociationController::class, 'getServices'])->name('associations.get-services');

            Route::get('/employment-opportunity-plans', [EmploymentOpportunityPlanController::class, 'indexkebele'])->name('employment-opportunity-plans.indexkebele');
            Route::put('/employment-opportunity-plans/{id}/approve', [EmploymentOpportunityPlanController::class, 'approve'])->name('employment-opportunity-plans.approve');







        });

        // Logout
        Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth', 'role:5,4'])->prefix('sector')->name('sector.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'sectorDashboard'])->name('sector.dashboard');

        // passandprofile
        Route::get('/profile/{uuid}', [UserController::class, 'profile'])->name('profile.users');
        Route::get('/profile/security/{uuid}', [UserController::class, 'changePasswordForm'])->name('users.password');

        Route::get('crop-plans', [KebeleCropPlanController::class, 'index'])->name('crop-plans.index');
        Route::get('crop-fertilizer', [KebeleCropPlanController::class, 'kebelefertlizer'])->name('fertlizer-plans.index');
        Route::get('crop-plans/create', [KebeleCropPlanController::class, 'create'])->name('crop-plans.create');
        Route::post('crop-plans/store', [KebeleCropPlanController::class, 'store'])->name('crop-plans.store');
        Route::get('crop-plans/{id}/edit', [KebeleCropPlanController::class, 'edit'])->name('crop-plans.editcrop');
        Route::put('crop-plans/{id}', [KebeleCropPlanController::class, 'update'])->name('crop-plans.update');


        Route::get('activityplan-plans/{id}', [KebeleCropPlanController::class, 'activityplan'])->name('crop-plans.activityplan');
        Route::get('production/activityplan-plans/{id}', [KebeleCropPlanController::class, 'productionactivityplan'])->name('crop-plans.productionactivityplan');

        Route::get('crop-plans/{id}/edit/fertilizer', [KebeleCropPlanController::class, 'editfertilizer'])->name('crop-plans.editfertilizer');
        Route::delete('crop-plans/{id}', [KebeleCropPlanController::class, 'destroy'])->name('crop-plans.destroy');


        Route::get('activityplan-plans', [KebeleCropPlanController::class, 'plantime'])->name('crop-plans.plantime');
        Route::get('productiontime-plans', [KebeleCropPlanController::class, 'productiontime'])->name('crop-plans.productiontime');
        Route::get('fertilizer-plans', [KebeleCropPlanController::class, 'fertilizerTime'])->name('crop-plans.fertilizertime');
          Route::get('/crop-plans/export/excel', [KebeleCropPlanController::class, 'exportExcel'])->name('crop-plans.export');


          // Fertilizer distribution routes
        Route::get('/fertilizer-plans/{id}/edit', [KebeleCropPlanController::class, 'fertilizerPlan'])->name('fertilizer-plans.edit');
        Route::put('/fertilizer-plans/{id}', [KebeleCropPlanController::class, 'updateFertilizerDistribution'])->name('fertilizer-plans.update');
            // OR if you want CSV
            Route::get('/crop-plans/export/csv', [KebeleCropPlanController::class, 'exportCsv'])->name('crop-plans.export.csv');


          Route::get('/mechanizations/{id}/plan', [MechanizationController::class, 'mechanizationPlan'])
        ->name('mechanizations.plan');
        Route::put('/mechanization-plans/{id}', [MechanizationController::class, 'updateMechanizationPlan'])
        ->name('mechanization-plans.update');
        
        Route::get('/mechanizations/timeline', [MechanizationController::class, 'mechanizationTime'])->name('mechanizations.timeline');
        Route::get('/mechanizations/export-excel', [MechanizationController::class, 'exportMechanizationExcel'])->name('mechanizations.export-excel');

Route::get('/gabbina_biyyee/{id}/plan', [GabbinaBiyyeeController::class, 'compostPlan'])
    ->name('gabbina_biyyee.plan');
Route::put('/compost-plans/{id}', [GabbinaBiyyeeController::class, 'updateCompostPlan'])
    ->name('compost-plans.update');

    Route::get('/gabbina_biyyee/timeline', [GabbinaBiyyeeController::class, 'compostTime'])->name('gabbina_biyyee.timeline');
Route::get('/gabbina_biyyee/export-excel', [GabbinaBiyyeeController::class, 'exportCompostExcel'])->name('gabbina_biyyee.export-excel');

        // Cascading AJAX routes
        Route::get('crops-by-year/{year}', [KebeleCropPlanController::class, 'getCropTypesByYear'])->name('crops.byYear');

        Route::get('crops-by-year-type/{year}/{croptype}', [KebeleCropPlanController::class, 'getCropsByYearAndType'])->name('crops.byYearAndType');
        Route::get('crop-plans/{id}/edit/fertilizer', [KebeleCropPlanController::class, 'editfertilizer'])
            ->name('crop-plans.editfertilizer');

        Route::put('crop-plans/{id}/update/fertilizer', [KebeleCropPlanController::class, 'updatefertilizer'])
            ->name('crop-plans.updatefertilizer');

        Route::post('crop-plans/import', [KebeleCropPlanController::class, 'importExcel'])
            ->name('crop-plans.import');

        Route::get('crop-plans/template/download', [KebeleCropPlanController::class, 'downloadTemplate'])
            ->name('crop-plans.download-template');

        Route::get('crop-plans/import', [KebeleCropPlanController::class, 'showImportForm'])->name('crop-plans.import.form');
        Route::post('crop-plans/import', [KebeleCropPlanController::class, 'import'])->name('crop-plans.import');


        Route::get('/gabbina-biyyee/create', [GabbinaBiyyeeController::class, 'create'])->name('gabbina_biyyee.create');
        Route::post('/gabbina-biyyee/store', [GabbinaBiyyeeController::class, 'store'])->name('gabbina_biyyee.store');

        // Add these to your web.php routes
        Route::get('/gabbina-biyyee', [GabbinaBiyyeeController::class, 'index'])->name('gabbina_biyyee.index');
        Route::get('/gabbina-biyyee/{id}/edit', [GabbinaBiyyeeController::class, 'edit'])->name('gabbina_biyyee.edit');
        Route::put('/gabbina-biyyee/{id}', [GabbinaBiyyeeController::class, 'update'])->name('gabbina_biyyee.update');
        Route::delete('/gabbina-biyyee/{id}', [GabbinaBiyyeeController::class, 'destroy'])->name('gabbina_biyyee.destroy');

        // Seedling Preparation Routes

        Route::get('/seedling-preparations', [SeedlingPreparationController::class, 'index'])->name('seedling-preparations.index');
        Route::get('/seedling-preparations/create', [SeedlingPreparationController::class, 'create'])->name('seedling-preparations.create');
        Route::post('/seedling-preparations', [SeedlingPreparationController::class, 'store'])->name('seedling-preparations.store');
        Route::get('/seedling-preparations/{id}/edit', [SeedlingPreparationController::class, 'edit'])->name('seedling-preparations.edit');

        Route::put('/seedling-preparations/{id}', [SeedlingPreparationController::class, 'update'])->name('seedling-preparations.update');

        Route::get('/seedling-preparations/{id}/editpreparation', [SeedlingPreparationController::class, 'editpreparation'])->name('seedling-preparations.editpreparation');

        Route::put('/seedling-preparations/update/{id}', [SeedlingPreparationController::class, 'updatePreparation'])
            ->name('seedling-preparations.updatepreparation');

        Route::delete('/seedling-preparations/{id}', [SeedlingPreparationController::class, 'destroy'])->name('seedling-preparations.destroy');

        // AJAX route for getting seedlings by type
        Route::get('/seedling-preparations/seedlings-by-type/{typeId}', [SeedlingPreparationController::class, 'getSeedlingsByType']);
        // AJAX route for getting group details
        Route::get('/seedling-preparations/group-details/{year}/{typeId}', [SeedlingPreparationController::class, 'getGroupDetails'])->name('seedling-preparations.group-details');

        Route::get('/livestock-plans', [LivestockPlanController::class, 'index'])->name('livestock_plans.index');
        Route::get('/livestock-plans/create', [LivestockPlanController::class, 'create'])->name('livestock_plans.create');
        Route::post('/livestock-plans/store', [LivestockPlanController::class, 'store'])->name('livestock_plans.store');
        Route::get('/livestock-plans/{id}/edit', [LivestockPlanController::class, 'edit'])->name('livestock_plans.edit');
        Route::get('/livestock-plans/{id}/product', [LivestockPlanController::class, 'editproduct'])->name('livestock_plans.editproduct');
        Route::put('/livestock-plans/{id}/update', [LivestockPlanController::class, 'update'])->name('livestock_plans.update');
        Route::delete('/livestock-plans/{id}/delete', [LivestockPlanController::class, 'destroy'])->name('livestock_plans.destroy');
        Route::get('/livestock-plans/{id}', [LivestockPlanController::class, 'show'])->name('livestock_plans.show');

        Route::get('/compost-report', [GabbinaBiyyeeController::class, 'index'])->name('compost.report');
        Route::get('/woreda/compost-report', [GabbinaBiyyeeController::class, 'woreda'])->name('compost.report.woreda');
        Route::prefix('mechanizations')->group(function () {
            Route::get('/', [MechanizationController::class, 'index'])->name('mechanizations.index');
            Route::get('/create', [MechanizationController::class, 'create'])->name('mechanizations.create');
            Route::post('/store', [MechanizationController::class, 'store'])->name('mechanizations.store');
            Route::get('/{id}/edit', [MechanizationController::class, 'edit'])->name('mechanizations.edit');
            Route::put('/{id}/update', [MechanizationController::class, 'update'])->name('mechanizations.update');
            Route::get('/{id}', [MechanizationController::class, 'show'])->name('mechanizations.show');
            Route::delete('/{id}', [MechanizationController::class, 'destroy'])->name('mechanizations.destroy');



        });

          // Environmental Protection Plans
            Route::get('/environmental-plans', [EnvironmentalProtectionPlanController::class, 'index'])->name('environmental-plans.index');
            Route::get('/environmental-plans/create', [EnvironmentalProtectionPlanController::class, 'create'])->name('environmental-plans.create');
            Route::post('/environmental-plans', [EnvironmentalProtectionPlanController::class, 'store'])->name('environmental-plans.store');
            Route::get('/environmental-plans/{id}/edit', [EnvironmentalProtectionPlanController::class, 'edit'])->name('environmental-plans.edit');
            Route::put('/environmental-plans/{id}', [EnvironmentalProtectionPlanController::class, 'update'])->name('environmental-plans.update');
            Route::delete('/environmental-plans/{id}', [EnvironmentalProtectionPlanController::class, 'destroy'])->name('environmental-plans.destroy');

Route::get('/natural-plans', [NaturalResourcePlanController::class, 'index'])->name('natural-plans.index');
    Route::get('/natural-plans/create', [NaturalResourcePlanController::class, 'create'])->name('natural-plans.create');
    Route::post('/natural-plans/store', [NaturalResourcePlanController::class, 'store'])->name('natural-plans.store');
    Route::get('/natural-plans/{id}/edit', [NaturalResourcePlanController::class, 'edit'])->name('natural-plans.edit');
    Route::get('/natural-plans/{id}/product', [NaturalResourcePlanController::class, 'editproduct'])->name('natural-plans.editproduct');
    Route::put('/natural-plans/{id}/update', [NaturalResourcePlanController::class, 'update'])->name('natural-plans.update');
    Route::delete('/natural-plans/{id}/delete', [NaturalResourcePlanController::class, 'destroy'])->name('natural-plans.destroy');


    Route::get('/association-expansion-plans', [AssociationExpansionPlanController::class, 'index'])->name('association-expansion-plans.index');
Route::get('/association-expansion-plans/create', [AssociationExpansionPlanController::class, 'create'])->name('association-expansion-plans.create');
Route::post('/association-expansion-plans', [AssociationExpansionPlanController::class, 'store'])->name('association-expansion-plans.store');
Route::get('/association-expansion-plans/{id}/edit', [AssociationExpansionPlanController::class, 'edit'])->name('association-expansion-plans.edit');
Route::put('/association-expansion-plans/{id}', [AssociationExpansionPlanController::class, 'update'])->name('association-expansion-plans.update');
Route::delete('/association-expansion-plans/{id}', [AssociationExpansionPlanController::class, 'destroy'])->name('association-expansion-plans.destroy');

Route::get('/association-types', [AssociationTypeController::class, 'index'])->name('association_types.index');
Route::get('/association-types/create', [AssociationTypeController::class, 'create'])->name('association_types.create');
Route::post('/association-types/store', [AssociationTypeController::class, 'store'])->name('association_types.store');
Route::get('/association-types/edit/{id}', [AssociationTypeController::class, 'edit'])->name('association_types.edit');
Route::put('/association-types/update/{id}', [AssociationTypeController::class, 'update'])->name('association_types.update');
Route::delete('/association-types/delete/{id}', [AssociationTypeController::class, 'destroy'])->name('association_types.destroy');



// Building Community Institution Plan Routes

    Route::get('/building-community-institution-plans', [BuildingCommunityInstitutionPlanController::class, 'index'])
        ->name('building-community-institution-plans.index');

    Route::get('/building-community-institution-plans/create', [BuildingCommunityInstitutionPlanController::class, 'create'])
        ->name('building-community-institution-plans.create');

    Route::post('/building-community-institution-plans/store', [BuildingCommunityInstitutionPlanController::class, 'store'])
        ->name('building-community-institution-plans.store');

    Route::get('/building-community-institution-plans/{id}/edit', [BuildingCommunityInstitutionPlanController::class, 'edit'])
        ->name('building-community-institution-plans.edit');

    Route::put('/building-community-institution-plans/{id}/update', [BuildingCommunityInstitutionPlanController::class, 'update'])
        ->name('building-community-institution-plans.update');

    Route::delete('/building-community-institution-plans/{id}/destroy', [BuildingCommunityInstitutionPlanController::class, 'destroy'])
        ->name('building-community-institution-plans.destroy');
        Route::get('/building-community-institution-plans/check-existing', [BuildingCommunityInstitutionPlanController::class, 'checkExisting'])->name('building-community-institution-plans.check-existing');



// Building Powers Institution Plan Routes

    Route::get('/building-power-institution-plans', [BuildingPowerInstitutionPlanController::class, 'index'])
        ->name('building-power-institution-plans.index');

    Route::get('/building-power-institution-plans/create', [BuildingPowerInstitutionPlanController::class, 'create'])
        ->name('building-power-institution-plans.create');

    Route::post('/building-power-institution-plans/store', [BuildingPowerInstitutionPlanController::class, 'store'])
        ->name('building-power-institution-plans.store');

    Route::get('/building-power-institution-plans/{id}/edit', [BuildingPowerInstitutionPlanController::class, 'edit'])
        ->name('building-power-institution-plans.edit');

    Route::put('/building-power-institution-plans/{id}/update', [BuildingPowerInstitutionPlanController::class, 'update'])
        ->name('building-power-institution-plans.update');

    Route::delete('/building-power-institution-plans/{id}/destroy', [BuildingPowerInstitutionPlanController::class, 'destroy'])
        ->name('building-power-institution-plans.destroy');
        Route::get('/building-power-institution-plans/check-existing', [BuildingPowerInstitutionPlanController::class, 'checkExisting'])->name('building-community-institution-plans.check-existing');
 
// Building deenagdee Institution Plan Routes

    Route::get('/building-power-institution-plans', [BuildingPowerInstitutionPlanController::class, 'index'])
        ->name('building-power-institution-plans.index');

    Route::get('/building-power-institution-plans/create', [BuildingPowerInstitutionPlanController::class, 'create'])
        ->name('building-power-institution-plans.create');

    Route::post('/building-power-institution-plans/store', [BuildingPowerInstitutionPlanController::class, 'store'])
        ->name('building-power-institution-plans.store');

    Route::get('/building-power-institution-plans/{id}/edit', [BuildingPowerInstitutionPlanController::class, 'edit'])
        ->name('building-power-institution-plans.edit');

    Route::put('/building-power-institution-plans/{id}/update', [BuildingPowerInstitutionPlanController::class, 'update'])
        ->name('building-power-institution-plans.update');

    Route::delete('/building-power-institution-plans/{id}/destroy', [BuildingPowerInstitutionPlanController::class, 'destroy'])
        ->name('building-power-institution-plans.destroy');
        Route::get('/building-power-institution-plans/check-existing', [BuildingPowerInstitutionPlanController::class, 'checkExisting'])->name('building-community-institution-plans.check-existing');
 
        // Building economic Institution Plan Routes

    Route::get('/building-economic-institution-plans', [BuildingEconomicInstitutionPlanController::class, 'index'])
        ->name('building-economic-institution-plans.index');

    Route::get('/building-economic-institution-plans/create', [BuildingEconomicInstitutionPlanController::class, 'create'])
        ->name('building-economic-institution-plans.create');

    Route::post('/building-economic-institution-plans/store', [BuildingEconomicInstitutionPlanController::class, 'store'])
        ->name('building-economic-institution-plans.store');

    Route::get('/building-economic-institution-plans/{id}/edit', [BuildingEconomicInstitutionPlanController::class, 'edit'])
        ->name('building-economic-institution-plans.edit');

    Route::put('/building-economic-institution-plans/{id}/update', [BuildingEconomicInstitutionPlanController::class, 'update'])
        ->name('building-economic-institution-plans.update');

    Route::delete('/building-economic-institution-plans/{id}/destroy', [BuildingEconomicInstitutionPlanController::class, 'destroy'])
        ->name('building-economic-institution-plans.destroy');
        Route::get('/building-economic-institution-plans/check-existing', [BuildingEconomicInstitutionPlanController::class, 'checkExisting'])->name('building-community-institution-plans.check-existing');

    // Employment Opportunity Plans
    Route::get('/employment-opportunity-plans', [EmploymentOpportunityPlanController::class, 'index'])->name('employment-opportunity-plans.index');
    Route::get('/employment-opportunity-plans/create', [EmploymentOpportunityPlanController::class, 'create'])->name('employment-opportunity-plans.create');
    Route::post('/employment-opportunity-plans', [EmploymentOpportunityPlanController::class, 'store'])->name('employment-opportunity-plans.store');
    Route::get('/employment-opportunity-plans/{id}/edit', [EmploymentOpportunityPlanController::class, 'edit'])->name('employment-opportunity-plans.edit');
    Route::put('/employment-opportunity-plans/{id}', [EmploymentOpportunityPlanController::class, 'update'])->name('employment-opportunity-plans.update');
    Route::delete('/employment-opportunity-plans/{id}', [EmploymentOpportunityPlanController::class, 'destroy'])->name('employment-opportunity-plans.destroy');
    Route::get('/employment-opportunity-plans/check-existing', [EmploymentOpportunityPlanController::class, 'checkExisting'])->name('employment-opportunity-plans.check-existing');

    });

    Route::middleware('role:6')->prefix('da')->group(function () {
        Route::get('/da-dashboard', [DashboardController::class, 'daDashboard'])->name('da.dashboard');

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

Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
Route::get('/farmers/create', [FarmerController::class, 'create'])->name('farmers.create');
Route::post('/farmers/store', [FarmerController::class, 'store'])->name('farmers.store');
Route::get('/farmers/edit/{id}', [FarmerController::class, 'edit'])->name('farmers.edit');
Route::get('/farmers/show/{id}', [FarmerController::class, 'show'])->name('farmers.show');
// Route::post('/farmers/update/{id}', [FarmerController::class, 'update'])->name('farmers.update');
Route::put('/farmers/update/{id}', [FarmerController::class, 'update'])->name('farmers.update');

Route::delete('/farmers/destroy/{id}', [FarmerController::class, 'destroy'])->name('farmers.destroy');

// dependent dropdowns
Route::get('/get-woredas/{zone_id}', [FarmerController::class, 'getWoredas']);
Route::get('/get-kebeles/{woreda_id}', [FarmerController::class, 'getKebeles']);
Route::get('/get-unions/{zone_id}', [FarmerController::class, 'getUnions']);
Route::get('/get-associations/{union_id}', [FarmerController::class, 'getAssociations']);
Route::post('/farmers/update/{id}', [FarmerController::class, 'update'])->name('farmers.update');

Route::get('farmers/{farmer}/land-edit', [FarmerController::class, 'editLand'])->name('farmers.land.edit');
// Route::post('farmers/{farmer}/land-update', [FarmerController::class, 'updateLand'])->name('farmers.land.update');
Route::put('/farmers/{id}/land-type-details', [FarmerController::class, 'updateLandTypeDetails'])
    ->name('farmers.updateLandTypeDetails');

Route::get('/fertilizers/type', [FertilizerTypeController::class, 'index'])->name('fertilizer.type.index');
Route::get('/fertilizers/create', [FertilizerTypeController::class, 'create'])->name('fertilizer.create');
Route::post('/fertilizers/store', [FertilizerTypeController::class, 'store'])->name('fertilizer.store');
Route::get('/fertilizers/{id}/edit', [FertilizerTypeController::class, 'edit'])->name('fertilizer.edit');
Route::put('/fertilizers/{id}/update', [FertilizerTypeController::class, 'update'])->name('fertilizer.update');
Route::delete('/fertilizers/{id}/delete', [FertilizerTypeController::class, 'destroy'])->name('fertilizer.delete');

Route::get('/fertilizers', [FertilizerController::class, 'index'])->name('fertilizer.index');
Route::get('/fertilizers/create', [FertilizerController::class, 'create'])->name('create.fertilizer');
Route::post('/fertilizers/store', [FertilizerController::class, 'store'])->name('fertilizer.store');
Route::get('/fertilizers/{id}/edit', [FertilizerController::class, 'edit'])->name('fertilizer.edit');
Route::put('/fertilizers/{id}/update', [FertilizerController::class, 'update'])->name('fertilizer.update');
Route::delete('/fertilizers/{id}/delete', [FertilizerController::class, 'destroy'])->name('fertilizer.delete');

Route::get('/soils', [SoilTypeController::class, 'index'])->name('soil.index');
Route::get('/soils/create', [SoilTypeController::class, 'create'])->name('soil.create');
Route::post('/soils/store', [SoilTypeController::class, 'store'])->name('soil.store');
Route::get('/soils/{id}/edit', [SoilTypeController::class, 'edit'])->name('soil.edit');
Route::put('/soils/{id}/update', [SoilTypeController::class, 'update'])->name('soil.update');
Route::delete('/soils/{id}/delete', [SoilTypeController::class, 'destroy'])->name('soil.delete');

// Parcel Crop Plan routes
Route::get('/farmers/{farmer}/parcel-crop-plans', [ParcelCropPlanController::class, 'index'])->name('parcel-crop-plans.index');
Route::get('/farmers/{farmer}/parcel-crop-plans/create', [ParcelCropPlanController::class, 'create'])->name('parcel-crop-plans.create');
Route::post('/farmers/{farmer}/parcel-crop-plans', [ParcelCropPlanController::class, 'store'])->name('parcel-crop-plans.store');

Route::get('/api/crop-types-by-year/{year}', [ParcelCropPlanController::class, 'getCropTypesByYear']);
Route::get('/api/crops-by-type-and-year/{croptype_id}/{year}', [ParcelCropPlanController::class, 'getCropsByTypeAndYear']);
Route::post('/api/crop-configuration', [ParcelCropPlanController::class, 'getConfiguration']); // ✅ fixed name

Route::get('parcel-crop-plans/edit/{id}', [ParcelCropPlanController::class, 'edit'])->name('parcel-crop-plans.edit');
Route::put('parcel-crop-plans/update/{id}', [ParcelCropPlanController::class, 'update'])->name('parcel-crop-plans.update');
Route::delete('/parcel-crop-plans/{id}', [ParcelCropPlanController::class, 'destroy'])->name('parcel-crop-plans.destroy');

Route::middleware('role:1')->prefix('admin')->group(function () {

    // Important: Put 'create' BEFORE {lang} to avoid conflicts
    Route::get('/languages/create', [LanguageController::class, 'create'])->name('languages.create');
    Route::post('/languages/store', [LanguageController::class, 'store'])->name('languages.store');

    Route::get('/languages/', [LanguageController::class, 'index'])->name('languages.list');
    Route::get('/languages/{lang}/edit', [LanguageController::class, 'edit'])->name('languages.edit');
    Route::put('/languages/{lang}/update', [LanguageController::class, 'update'])->name('languages.update');
    Route::get('/languages/{lang}', [LanguageController::class, 'show'])->name('languages.show');

    // Switch language shortcut

    Route::get('/languages/switch/{lang}', [LanguageController::class, 'switchLanguage'])->name('languages.switch');

});

// Route::post('/parcel-crop-plans/api/fertilizer-recommendation', [ParcelCropPlanController::class, 'getFertilizerRecommendation']);

Route::get('/set-language/{language}', function ($language) {
    if (in_array($language, ['en', 'am', 'or'])) {
        Session::put('locale', $language);
    }

    return redirect()->back();
})->name('set.language');

// Route::get('/weekly-plans', [WeeklyPlanController::class, 'index'])->name('weekly-plans.index');
// Route::get('/weekly-plans/create', [WeeklyPlanController::class, 'create'])->name('weekly-plans.create');
// Route::post('/weekly-plans', [WeeklyPlanController::class, 'store'])->name('weekly-plans.store');
