<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Web;
use Illuminate\Support\Facades\Route;

Route::get('/', [Web\HomeController::class, 'index'])->name('home');
Route::get('/tours', [Web\TourController::class, 'index'])->name('tours.index');
Route::get('/tours/{slug}', [Web\TourController::class, 'show'])->name(
    'tours.show',
);
Route::post('/consultation', [Web\ConsultationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('consultation.store');

Route::prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name(
            'login',
        );
        Route::middleware('admin.jwt')->group(function (): void {
            Route::get('/', Admin\DashboardController::class)
                ->middleware('permission:dashboard.view')
                ->name('dashboard');
            Route::get('landing-editor', [
                Admin\LandingEditorController::class,
                'index',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor');
            Route::put('landing-editor/hero-image', [
                Admin\LandingEditorController::class,
                'updateHeroImage',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.hero-image');
            Route::put('landing-editor/destinations/{destination}', [
                Admin\LandingEditorController::class,
                'updateDestination',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.destinations.update');
            Route::post('landing-editor/destinations', [
                Admin\LandingEditorController::class,
                'storeDestination',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.destinations.store');
            Route::delete('landing-editor/destinations/{destination}', [
                Admin\LandingEditorController::class,
                'destroyDestination',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.destinations.destroy');
            Route::post('landing-editor/tours', [
                Admin\LandingEditorController::class,
                'storeTour',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.tours.store');
            Route::put('landing-editor/tours/{tour}', [
                Admin\LandingEditorController::class,
                'updateTour',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.tours.update');
            Route::delete('landing-editor/tours/{tour}', [
                Admin\LandingEditorController::class,
                'destroyTour',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.tours.destroy');
            Route::put('landing-editor/destinations-order', [
                Admin\LandingEditorController::class,
                'reorderDestinations',
            ])
                ->middleware('permission:pages.update')
                ->name('landing-editor.destinations.reorder');
            Route::get('destinations', [
                Admin\DestinationController::class,
                'index',
            ])
                ->middleware('permission:destinations.view')
                ->name('destinations.index');
            Route::get('destinations/create', [
                Admin\DestinationController::class,
                'create',
            ])
                ->middleware('permission:destinations.create')
                ->name('destinations.create');
            Route::post('destinations', [
                Admin\DestinationController::class,
                'store',
            ])
                ->middleware('permission:destinations.create')
                ->name('destinations.store');
            Route::get('destinations/{destination}/edit', [
                Admin\DestinationController::class,
                'edit',
            ])
                ->middleware('permission:destinations.update')
                ->name('destinations.edit');
            Route::put('destinations/{destination}', [
                Admin\DestinationController::class,
                'update',
            ])
                ->middleware('permission:destinations.update')
                ->name('destinations.update');
            Route::delete('destinations/{destination}', [
                Admin\DestinationController::class,
                'destroy',
            ])
                ->middleware('permission:destinations.delete')
                ->name('destinations.destroy');

            Route::get('included-services', [
                Admin\IncludedServiceController::class,
                'index',
            ])
                ->middleware('permission:tours.view')
                ->name('included-services.index');
            Route::get('included-services/create', [
                Admin\IncludedServiceController::class,
                'create',
            ])
                ->middleware('permission:tours.create')
                ->name('included-services.create');
            Route::post('included-services', [
                Admin\IncludedServiceController::class,
                'store',
            ])
                ->middleware('permission:tours.create')
                ->name('included-services.store');
            Route::get('included-services/{included_service}/edit', [
                Admin\IncludedServiceController::class,
                'edit',
            ])
                ->middleware('permission:tours.update')
                ->name('included-services.edit');
            Route::put('included-services/{included_service}', [
                Admin\IncludedServiceController::class,
                'update',
            ])
                ->middleware('permission:tours.update')
                ->name('included-services.update');
            Route::delete('included-services/{included_service}', [
                Admin\IncludedServiceController::class,
                'destroy',
            ])
                ->middleware('permission:tours.delete')
                ->name('included-services.destroy');

            Route::get('tours', [Admin\TourController::class, 'index'])
                ->middleware('permission:tours.view')
                ->name('tours.index');
            Route::get('tours/create', [Admin\TourController::class, 'create'])
                ->middleware('permission:tours.create')
                ->name('tours.create');
            Route::post('tours', [Admin\TourController::class, 'store'])
                ->middleware('permission:tours.create')
                ->name('tours.store');
            Route::get('tours/{tour}/edit', [
                Admin\TourController::class,
                'edit',
            ])
                ->middleware('permission:tours.update')
                ->name('tours.edit');
            Route::put('tours/{tour}', [Admin\TourController::class, 'update'])
                ->middleware('permission:tours.update')
                ->name('tours.update');
            Route::delete('tours/{tour}', [
                Admin\TourController::class,
                'destroy',
            ])
                ->middleware('permission:tours.delete')
                ->name('tours.destroy');

            Route::get('consultations', [
                Admin\ConsultationRequestController::class,
                'index',
            ])
                ->middleware('permission:consultations.view')
                ->name('consultations.index');
            Route::get('consultations/{consultation}/edit', [
                Admin\ConsultationRequestController::class,
                'edit',
            ])
                ->middleware('permission:consultations.update')
                ->name('consultations.edit');
            Route::put('consultations/{consultation}', [
                Admin\ConsultationRequestController::class,
                'update',
            ])
                ->middleware('permission:consultations.update')
                ->name('consultations.update');
            Route::delete('consultations/{consultation}', [
                Admin\ConsultationRequestController::class,
                'destroy',
            ])
                ->middleware('permission:consultations.update')
                ->name('consultations.destroy');

            Route::get('pages', [Admin\PageController::class, 'index'])
                ->middleware('permission:pages.view')
                ->name('pages.index');
            Route::get('pages/create', [Admin\PageController::class, 'create'])
                ->middleware('permission:pages.create')
                ->name('pages.create');
            Route::post('pages', [Admin\PageController::class, 'store'])
                ->middleware('permission:pages.create')
                ->name('pages.store');
            Route::get('pages/{page}/edit', [
                Admin\PageController::class,
                'edit',
            ])
                ->middleware('permission:pages.update')
                ->name('pages.edit');
            Route::put('pages/{page}', [Admin\PageController::class, 'update'])
                ->middleware('permission:pages.update')
                ->name('pages.update');
            Route::delete('pages/{page}', [
                Admin\PageController::class,
                'destroy',
            ])
                ->middleware('permission:pages.delete')
                ->name('pages.destroy');

            Route::get('settings', [Admin\SettingController::class, 'index'])
                ->middleware('permission:settings.view')
                ->name('settings.index');
            Route::put('settings/theme', [
                Admin\SettingController::class,
                'updateTheme',
            ])
                ->middleware('permission:settings.update')
                ->name('settings.theme.update');
            Route::get('settings/{setting}/edit', [
                Admin\SettingController::class,
                'edit',
            ])
                ->middleware('permission:settings.update')
                ->name('settings.edit');
            Route::put('settings/{setting}', [
                Admin\SettingController::class,
                'update',
            ])
                ->middleware('permission:settings.update')
                ->name('settings.update');
        });
    });
