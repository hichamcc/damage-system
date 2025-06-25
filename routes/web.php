<?php

use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TruckController;
use App\Http\Controllers\Admin\ControlLinesController;
use App\Http\Controllers\Admin\ControlTemplatesController;
use App\Http\Controllers\User\ControlController;
use App\Http\Controllers\Admin\TruckTemplateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\User;
use App\Http\Controllers\Admin\TruckNumbersController;


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

    // Trucks Management
    Route::get('trucks', [TruckController::class, 'index'])->name('trucks.index');
    Route::get('trucks/create', [TruckController::class, 'create'])->name('trucks.create');
    Route::get('trucks/{truck}', [TruckController::class, 'show'])->name('trucks.show');
    Route::get('trucks/{truck}/edit', [TruckController::class, 'edit'])->name('trucks.edit');
    Route::post('trucks', [TruckController::class, 'store'])->name('trucks.store');
    Route::put('trucks/{truck}', [TruckController::class, 'update'])->name('trucks.update');
    Route::patch('trucks/{truck}', [TruckController::class, 'update'])->name('trucks.patch');
    Route::delete('trucks/d/{truck}', [TruckController::class, 'destroy'])->name('trucks.destroy');


    //truck templates
    Route::resource('truck-templates', TruckTemplateController::class);
    Route::patch('truck-templates/{truckTemplate}/toggle-status', [TruckTemplateController::class, 'toggleStatus'])
        ->name('truck-templates.toggle-status');

    //truck templates
    Route::resource('truck-templates', TruckTemplateController::class);
    
    // Additional truck routes for file management
    Route::delete('trucks/{truck}/attachments/{index}', [TruckController::class, 'removeAttachment'])
        ->name('trucks.attachments.remove');
    Route::get('trucks/{truck}/attachments/{index}/download', [TruckController::class, 'downloadAttachment'])
        ->name('trucks.attachments.download');

    // Control Templates Management (NEW)
    Route::resource('control-templates', ControlTemplatesController::class);
    Route::patch('control-templates/{controlTemplate}/toggle-active', [ControlTemplatesController::class, 'toggleActive'])
        ->name('control-templates.toggle-active');

    // Control Lines Management (UPDATED - View Only for User-Created Controls)
    Route::get('controls', [ControlLinesController::class, 'index'])->name('control.index');
    Route::get('controls/{controlLine}', [ControlLinesController::class, 'show'])->name('control.show');
    Route::get('controls/{controlLine}/compare', [ControlLinesController::class, 'compareChecks'])->name('control.compare');
    Route::get('controls/{controlLine}/damages', [ControlLinesController::class, 'damageReports'])->name('control.damages');
    
    // Damage Reports Management
    Route::get('damages', [ControlLinesController::class, 'allDamages'])->name('damages.index');
    Route::get('damages/{damage}', [ControlLinesController::class, 'showDamage'])->name('damages.show');
    Route::patch('damages/{damage}/status', [ControlLinesController::class, 'updateDamageStatus'])->name('damages.update-status');
    Route::patch('damages/{damage}/fixed', [ControlLinesController::class, 'markDamageFixed'])->name('damages.mark-fixed');
    Route::delete('damages/{damage}', [ControlLinesController::class, 'deleteDamage'])->name('damages.destroy');


    Route::resource('truck-numbers', TruckNumbersController::class);
    Route::post('truck-numbers/bulk-delete', [TruckNumbersController::class, 'bulkDelete'])->name('truck-numbers.bulk-delete');
    Route::post('truck-numbers/bulk-import', [TruckNumbersController::class, 'bulkImport'])->name('truck-numbers.bulk-import');

});



// User Routes (NEW - For Creating and Managing Their Own Controls)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    
    // User Dashboard - Create New Controls
    Route::get('dashboard', [ControlController::class, 'index'])->name('control.index');
    
    Route::post('control/create', [ControlController::class, 'store'])->name('control.store');
    Route::get('control/{controlLine}', [ControlController::class, 'show'])->name('control.show');
    
    // Check Forms
    Route::get('control/{controlLine}/start', [ControlController::class, 'start'])->name('control.start');
    Route::get('control/{controlLine}/exit', [ControlController::class, 'exit'])->name('control.exit');
    
    // API Routes for AJAX
    Route::get('api/active-template', [ControlController::class, 'getActiveTemplate'])->name('api.active-template');

    // Keep existing check completion routes (assuming you have CheckController for task completion)
    // Complete Task
    Route::put('tasks/{controlTask}/complete', [User\CheckController::class, 'completeTask'])->name('tasks.complete');
    
    // Report Damage
    Route::post('controls/{controlLine}/damage', [User\CheckController::class, 'reportDamage'])->name('controls.damage');
    
    // Submit Check Forms (if you have these methods)
    Route::post('controls/{controlLine}/start', [User\CheckController::class, 'submitStartCheck'])->name('controls.start.submit');
    Route::post('controls/{controlLine}/exit', [User\CheckController::class, 'submitExitCheck'])->name('controls.exit.submit');


     


});

// API Routes for AJAX calls
Route::middleware(['auth'])->group(function () {
    
    // Get all active truck templates (for admin template creation)
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