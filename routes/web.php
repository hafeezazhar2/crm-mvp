<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\AIController;
use Illuminate\Support\Facades\Route;
use App\Repositories\LeadRepository;

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', function (LeadRepository $leadRepository) {
        
        return inertia('Dashboard', [
            'auth' => [
                'user' => [
                    ...auth()->user()->toArray(),
                    'isAdmin' => auth()->user()->isAdmin // Ensure this property exists
                ]
            ],
            'leadStatusCounts' => $leadRepository->getStatusCounts(auth()->user())
        ]);
    })->name('dashboard');

    // Leads Routes
    Route::resource('leads', LeadController::class)->except(['show']);
    Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
    Route::post('leads/{lead}/notes', [LeadController::class, 'addNote'])->name('leads.notes.store');

    // Advisors Routes (Admin only)
    Route::middleware('can:viewAny,App\Models\User')->group(function () {
        Route::resource('advisors', AdvisorController::class);
    });

    // AI Routes
    Route::get('ai/policy-assistant', [AIController::class, 'policyAssistant'])->name('ai.policy-assistant');
    Route::post('ai/query-policy', [AIController::class, 'queryPolicy'])->name('ai.query-policy');
});
