<?php

use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TruckController;
use App\Http\Controllers\Admin\ControlLinesController;
use App\Http\Controllers\User ;
use App\Http\Controllers\Admin\TruckTemplateController;
use App\Http\Controllers\DashboardController;




Route::get('/', function () {
    return view('welcome');
})->name('home');



Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');  

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

      // GET routes
    Route::get('trucks', [TruckController::class, 'index'])->name('trucks.index');
    Route::get('trucks/create', [TruckController::class, 'create'])->name('trucks.create');
    Route::get('trucks/{truck}', [TruckController::class, 'show'])->name('trucks.show');
    Route::get('trucks/{truck}/edit', [TruckController::class, 'edit'])->name('trucks.edit');
    
    // POST routes
    Route::post('trucks', [TruckController::class, 'store'])->name('trucks.store');
    
    // PUT/PATCH routes
    Route::put('trucks/{truck}', [TruckController::class, 'update'])->name('trucks.update');
    Route::patch('trucks/{truck}', [TruckController::class, 'update'])->name('trucks.patch');
    
    // DELETE routes
    Route::delete('trucks/d/{truck}', [TruckController::class, 'destroy'])->name('trucks.destroy');
    
       // Additional truck routes for file management
       Route::delete('trucks/{truck}/attachments/{index}', [TruckController::class, 'removeAttachment'])
           ->name('trucks.attachments.remove');
       
       Route::get('trucks/{truck}/attachments/{index}/download', [TruckController::class, 'downloadAttachment'])
           ->name('trucks.attachments.download');

        // Control Lines Management
        Route::get('control', [ControlLinesController::class, 'index'])->name('control.index');
        Route::get('control/create', [ControlLinesController::class, 'create'])->name('control.create');
        Route::post('control', [ControlLinesController::class, 'store'])->name('control.store');
        Route::get('control/{controlLine}', [ControlLinesController::class, 'show'])->name('control.show');
        Route::get('control/{controlLine}/compare', [ControlLinesController::class, 'compareChecks'])->name('control.compare');
        Route::get('control/{controlLine}/edit', [ControlLinesController::class, 'edit'])->name('control.edit');
        Route::put('control/{controlLine}', [ControlLinesController::class, 'update'])->name('control.update');
        Route::delete('control/{controlLine}', [ControlLinesController::class, 'destroy'])->name('control.destroy');
        Route::post('control/{controlLine}/tasks', [ControlLinesController::class, 'addTask'])->name('control.tasks.add');
        Route::delete('control/{controlLine}/tasks/{task}', [ControlLinesController::class, 'removeTask'])->name('control.tasks.remove');
        Route::get('control/{controlLine}/damage-reports', [ControlLinesController::class, 'damageReports'])->name('control.damage-reports');
        Route::put('control/{controlLine}/damage/{damageReport}', [ControlLinesController::class, 'markDamageFixed'])->name('control.damage.mark-fixed');


         // Control damage reports
            Route::get('/control/{controlLine}/damages', [ControlLinesController::class, 'damageReports'])
            ->name('control.damages');

        // Mark damage as fixed
        Route::patch('/damage/{damage}/mark-fixed', [ControlLinesController::class, 'markDamageFixed'])
            ->name('damage.mark-fixed');

        // Update damage status (for marking as in repair)
        Route::patch('/damage/{damage}/status', [ControlLinesController::class, 'updateDamageStatus'])
            ->name('damage.update-status');

        // All damage reports (optional - for viewing all damages across all controls)
        Route::get('/damages', [ControlLinesController::class, 'allDamages'])
            ->name('damages.index');

        // Individual damage report view (optional)
        Route::get('/damage/{damage}', [ControlLinesController::class, 'showDamage'])
            ->name('damage.show');

        // Delete damage report (optional)
        Route::delete('/damage/{damage}', [ControlLinesController::class, 'deleteDamage'])
            ->name('damage.delete');



             // Truck Templates Resource Routes
    Route::resource('truck-templates', TruckTemplateController::class);
    
    // Additional template routes
    Route::patch('/truck-templates/{truckTemplate}/toggle-status', [TruckTemplateController::class, 'toggleStatus'])
        ->name('truck-templates.toggle-status');
    
    // API endpoint for getting templates (for AJAX in control creation)
    Route::get('/api/truck-templates', [TruckTemplateController::class, 'getTemplates'])
        ->name('truck-templates.api');
        });


// User Routes (for completing START/EXIT checks)
Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    
    // User Dashboard
    Route::get('dashboard', [User\CheckController::class, 'dashboard'])->name('dashboard');
    
    // Available Controls (assigned to user)
    Route::get('controls', [User\CheckController::class, 'myControls'])->name('controls');
    Route::get('controls/{controlLine}', [User\CheckController::class, 'show'])->name('controls.show');
    
    // Start Check
    Route::get('controls/{controlLine}/start', [User\CheckController::class, 'startCheck'])->name('controls.start');
    Route::post('controls/{controlLine}/start', [User\CheckController::class, 'submitStartCheck'])->name('controls.start.submit');
    
    // Exit Check
    Route::get('controls/{controlLine}/exit', [User\CheckController::class, 'exitCheck'])->name('controls.exit');
    Route::post('controls/{controlLine}/exit', [User\CheckController::class, 'submitExitCheck'])->name('controls.exit.submit');
    
    // Complete Task
    Route::put('tasks/{controlTask}/complete', [User\CheckController::class, 'completeTask'])->name('tasks.complete');
    
    // Report Damage
    Route::post('controls/{controlLine}/damage', [User\CheckController::class, 'reportDamage'])->name('controls.damage');
});

// In web.php (for web-based AJAX calls)
Route::middleware(['auth'])->group(function () {
    
    // Get all active templates (with optional filtering)
    Route::get('/admin/api/truck-templates', [TruckTemplateController::class, 'getTemplates'])
        ->name('admin.truck-templates.api');
    
    // Get specific template by ID
    Route::get('/admin/api/truck-templates/{id}', [TruckTemplateController::class, 'getTemplate'])
        ->name('admin.truck-templates.api.show');
    
    // Simple version without filtering
    Route::get('/admin/api/truck-templates/simple', [TruckTemplateController::class, 'getTemplatesSimple'])
        ->name('admin.truck-templates.api.simple');
});


require __DIR__.'/auth.php';
