<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\contentController; // Assuming this controller exists

Route::get('/', function () {
    return view('welcome');
});

// Routes only for authenticated and verified users
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard Route (assuming it lists videos or acts as a landing after login)
    Route::get('/dashboard', [VideoController::class, 'index'])->name('dashboard'); // Assuming VideoController::index is the dashboard view

    // Video Management Routes
    // Route to display the video upload form
    Route::get('/upload', [VideoController::class, 'create'])->name('videos.create');

    // Route to handle the submission of the video upload form
    // This is the one your upload.blade.php form should target.
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');

    // Route to display a list of all videos (if different from dashboard)
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index'); // If index should be separate from dashboard

    // Route to delete a specific video
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');

    // Content Management Routes (renamed for clarity and uniqueness)
    // Route to display content (e.g., an index of contents)
    Route::get('/contents', [contentController::class, 'index'])->name('contents.index');
    // Route to handle content submission/creation
    Route::post('/contents', [contentController::class, 'contents'])->name('contents.store');
});

require __DIR__.'/auth.php';