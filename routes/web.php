<?php

use App\Models\Activity;
use App\Models\Galery;
use App\Models\Video;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleryController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    $featuredActivity = Activity::query()->latest()->first();
    $featuredPosts = Activity::query()
        ->orderByDesc('id')
        ->skip(1)
        ->take(3)
        ->get();

    $featuredObservance = null;

    if ($featuredActivity) {
        $paragraphs = preg_split("/\R{2,}/", trim((string) $featuredActivity->body)) ?: [];
        $paragraphs = array_values(array_filter(array_map('trim', $paragraphs)));
        $image = $featuredActivity->image ? asset('storage/' . $featuredActivity->image) : null;

        $featuredObservance = [
            'stampDay' => Str::upper(Str::substr(Str::headline((string) $featuredActivity->type), 0, 2)),
            'stampRest' => $featuredActivity->name ?: 'Latest activity',
            'eventName' => $featuredActivity->name ?: $featuredActivity->title,
            'paragraphs' => array_slice($paragraphs, 0, 2),
            'image' => $image,
            'imageAlt' => $featuredActivity->title,
            'link' => route('activities.show', $featuredActivity->id),
        ];
    }

    $featuredPosts = $featuredPosts->map(function (Activity $activity) {
        $paragraphs = preg_split("/\R{2,}/", trim((string) $activity->body)) ?: [];
        $paragraphs = array_values(array_filter(array_map('trim', $paragraphs)));
        $excerptSource = implode(' ', array_slice($paragraphs, 0, 2));

        return [
            'kind' => 'photo',
            'tag' => Str::headline((string) $activity->type),
            'title' => $activity->name ?: $activity->title,
            'text' => Str::limit($excerptSource, 180),
            'image' => $activity->image ? asset('storage/' . $activity->image) : null,
            'imageAlt' => $activity->title,
            'link' => route('activities.show', $activity->id),
        ];
    });

    return view('pages.home', [
        'pageTitle' => null,
        'metaDescription' => 'MAWIE - The Movement of Afghan women in Exile. Advancing human rights, gender equality, and the protection of Afghan women through peaceful advocacy, education, and international cooperation.',
        'featuredPosts' => $featuredPosts,
        'featuredObservance' => $featuredObservance,
    ]);
})->name('home');

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
            'image' => $activity->image ? asset('storage/' . $activity->image) : null,
            'imageAlt' => $activity->title,
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
            'image' => $activity->image ? asset('storage/' . $activity->image) : null,
            'imageAlt' => $activity->title,
        ],
    ]);
})->name('activities.show');

Route::view('/about', 'pages.about', [
    'pageTitle' => 'About Us',
    'metaDescription' => 'Who we are, what we work toward, and the principles that guide us.',
    'aboutBlocks' => config('mawie.about_blocks'),
])->name('about');

Route::get('/media', function () {
    return view('pages.media', [
        'pageTitle' => 'Media',
        'metaDescription' => 'Photographs from events, commemorations, and meetings, along with short videos and statements as they become available.',
        'galeries' => Galery::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12, ['*'], 'gallery_page')
            ->withQueryString(),
        'videos' => Video::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12, ['*'], 'video_page')
            ->withQueryString(),
    ]);
})->name('media');

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.send');

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
