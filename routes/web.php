<?php

use App\Models\Activity;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleryController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::view('/', 'pages.home', [
    'pageTitle' => null,
    'metaDescription' => 'MAWIE - The Movement of Afghanistan Women in Exile. Advancing human rights, gender equality, and the protection of Afghan women through peaceful advocacy, education, and international cooperation.',
    'featuredPosts' => config('mawie.featured_posts'),
])->name('home');

Route::get('/activities', function () {
    $activities = Activity::query()
        ->orderBy('id')
        ->paginate(10);

    $activities->getCollection()->transform(function (Activity $activity) {
        $paragraphs = preg_split("/\R{2,}/", trim((string) $activity->body)) ?: [];
        $paragraphs = array_values(array_filter(array_map('trim', $paragraphs)));
        $excerptSource = implode(' ', array_slice($paragraphs, 0, 2));

        return [
            'id' => $activity->id,
            'tag' => Str::headline((string) $activity->type),
            'sub' => $activity->name ?: 'Advocacy',
            'title' => $activity->title,
            'paragraphs' => $paragraphs,
            'excerpt' => Str::limit($excerptSource, 240),
        ];
    });

    return view('pages.activities', [
        'pageTitle' => 'Activities',
        'metaDescription' => "A chronological record of MAWIE's public statements, commemorations, and engagements - a plainspoken account of what we've raised our voice on, and when.",
        'activities' => $activities,
    ]);
})->name('activities');

Route::get('/activities/{activity}', function (Activity $activity) {
    $paragraphs = preg_split("/\R{2,}/", trim((string) $activity->body)) ?: [];
    $paragraphs = array_values(array_filter(array_map('trim', $paragraphs)));

    return view('pages.activity', [
        'pageTitle' => $activity->title,
        'metaDescription' => Str::limit(strip_tags($activity->body), 160),
        'activity' => [
            'id' => $activity->id,
            'tag' => Str::headline((string) $activity->type),
            'sub' => $activity->name ?: 'Advocacy',
            'title' => $activity->title,
            'paragraphs' => $paragraphs,
        ],
    ]);
})->name('activities.show');

Route::view('/about', 'pages.about', [
    'pageTitle' => 'About Us',
    'metaDescription' => 'Who we are, what we work toward, and the principles that guide us.',
    'aboutBlocks' => config('mawie.about_blocks'),
])->name('about');

Route::view('/media', 'pages.media', [
    'pageTitle' => 'Media',
    'metaDescription' => 'Photographs from events, commemorations, and meetings, along with short videos and statements as they become available.',
])->name('media');

Route::view('/contact', 'pages.contact', [
    'pageTitle' => 'Contact',
    'metaDescription' => 'Feel free to contact us regarding our activities and to get involved.',
])->name('contact');

Route::get('/verify-member', [VerificationController::class, 'index'])->name('verify-member');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('activities', ActivityController::class)->except(['show']);
    Route::resource('galeries', GaleryController::class)->except(['show']);
    Route::resource('verifications', AdminVerificationController::class)->except(['show']);
    Route::resource('videos', VideoController::class)->except(['show']);
});
