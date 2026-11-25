<?php

use App\Models\Activity;
use App\Models\Galery;
use App\Models\News;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleryController;
use App\Http\Controllers\Admin\MemberCardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MessengerWebhookController;
use App\Http\Controllers\VerificationController;
use App\Support\YouTubeChannel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// Public pages: English at /, Dari at /fa. Views link with localized_route() so visitors stay in their language.
$publicRoutes = function () {
    Route::get('/', function () {
        $latestActivities = Activity::query()
            ->where('type', '!=', 'event')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(4)
            ->get();
        $featuredActivity = $latestActivities->first();
        $featuredPosts = $latestActivities->slice(1)->values();
        $latestEventRecord = Activity::query()
            ->where('type', 'event')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();

        $featuredObservance = null;

        if ($featuredActivity) {
            $paragraphs = array_slice($featuredActivity->paragraphs(), 0, 2);
            $image = $featuredActivity->image ? asset('storage/' . $featuredActivity->image) : null;

            $featuredObservance = [
                'stampDay' => Str::upper(Str::substr($featuredActivity->typeLabel(), 0, 2)),
                'stampRest' => $featuredActivity->translated('name') ?: __('Latest activity'),
                'eventName' => $featuredActivity->translated('name') ?: $featuredActivity->translated('title'),
                'paragraphs' => $paragraphs,
                'image' => $image,
                'imageAlt' => $featuredActivity->translated('title'),
                'link' => localized_route('activities.show', $featuredActivity->id),
            ];
        }

        $featuredPosts = $featuredPosts->map(function (Activity $activity) {
            $excerptSource = implode(' ', array_slice($activity->paragraphs(), 0, 2));

            return [
                'kind' => 'photo',
                'tag' => $activity->typeLabel(),
                'title' => $activity->translated('name') ?: $activity->translated('title'),
                'text' => Str::limit($excerptSource, 180),
                'image' => $activity->image ? asset('storage/' . $activity->image) : null,
                'imageAlt' => $activity->translated('title'),
                'link' => localized_route('activities.show', $activity->id),
            ];
        });

        $latestEvent = $latestEventRecord ? [
            'id' => $latestEventRecord->id,
            'date' => $latestEventRecord->created_at,
            'image' => $latestEventRecord->image ? asset('storage/'.$latestEventRecord->image) : null,
            'title' => $latestEventRecord->translated('name') ?: $latestEventRecord->translated('title'),
            'imageAlt' => $latestEventRecord->translated('title'),
            'text' => Str::limit(strip_tags($latestEventRecord->translated('body')), 210),
        ] : null;

        return view('pages.home', [
            'pageTitle' => null,
            'metaDescription' => __('MAWIE - The Movement of Afghan women in Exile. Advancing human rights, gender equality, and the protection of Afghan women through peaceful advocacy, education, and international cooperation.'),
            'featuredPosts' => $featuredPosts,
            'featuredObservance' => $featuredObservance,
            'latestEvent' => $latestEvent,
        ]);
    })->name('home');

    Route::get('/activities', function () {
        $activities = Activity::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);

        $activities->getCollection()->transform(function (Activity $activity) {
            $paragraphs = array_slice($activity->paragraphs(), 0, 2);

            return [
                'id' => $activity->id,
                'tag' => $activity->typeLabel(),
                'sub' => $activity->translated('name') ?: __('Advocacy'),
                'title' => $activity->translated('title'),
                'paragraphs' => $paragraphs,
                'excerpt' => Str::limit(implode(' ', $paragraphs), 240),
                'image' => $activity->image ? asset('storage/' . $activity->image) : null,
                'imageAlt' => $activity->translated('title'),
            ];
        });

        return view('pages.activities', [
            'pageTitle' => __('Activities'),
            'metaDescription' => __("A chronological record of MAWIE's public statements, commemorations, and engagements - a plainspoken account of what we've raised our voice on, and when."),
            'activities' => $activities,
        ]);
    })->name('activities');

    Route::get('/activities/{activity}', function (Activity $activity) {
        $paragraphs = $activity->paragraphs();
        $title = $activity->translated('title');

        return view('pages.activity', [
            'pageTitle' => $title,
            'metaDescription' => Str::limit(implode(' ', $paragraphs), 160),
            'activity' => [
                'id' => $activity->id,
                'tag' => $activity->typeLabel(),
                'sub' => $activity->translated('name') ?: __('Advocacy'),
                'title' => $title,
                'paragraphs' => $paragraphs,
                'image' => $activity->image ? asset('storage/' . $activity->image) : null,
                'imageAlt' => $title,
            ],
        ]);
    })->name('activities.show');

    Route::get('/about', function () {
        return view('pages.about', [
            'pageTitle' => __('About Us'),
            'metaDescription' => __('Who we are, what we work toward, and the principles that guide us.'),
            'aboutBlocks' => config('mawie.about_blocks'),
        ]);
    })->name('about');

    Route::get('/support-us', function () {
        return view('pages.support', [
            'pageTitle' => __('Support Us'),
            'metaDescription' => __('Support the Movement of Afghan Women in Exile with a donation, your time, or your voice.'),
            'donationUrl' => config('mawie.support.donation_url'),
            'bankDetails' => config('mawie.support.bank_details', []),
        ]);
    })->name('support');

    Route::get('/privacy-policy', function () {
        return view('pages.privacy', [
            'pageTitle' => __('Privacy Policy'),
            'metaDescription' => __('How the Movement of Afghan Women in Exile handles your information on this website, Facebook and Instagram.'),
        ]);
    })->name('privacy');

    Route::get('/media', function () {
        $news = News::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('pages.news', [
            'pageTitle' => __('News'),
            'metaDescription' => __('News and updates from the Movement of Afghan Women in Exile.'),
            'news' => $news,
        ]);
    })->name('media');

    Route::get('/media/{news}', function (News $news) {
        $title = $news->translated('title');

        return view('pages.news-item', [
            'pageTitle' => $title,
            'metaDescription' => Str::limit(implode(' ', $news->paragraphs()), 160),
            'newsItem' => $news,
        ]);
    })->name('media.show');

    Route::get('/gallery', function () {
        $galeries = Galery::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12, ['*'], 'gallery_page')
            ->withQueryString();
        return view('pages.gallery', [
            'pageTitle' => __('Gallery'),
            'metaDescription' => __('Photographs from events, commemorations, and meetings, along with short videos and statements as they become available.'),
            'galeries' => $galeries,
            'videos' => YouTubeChannel::latestVideos(),
            'youtubeUrl' => config('mawie.youtube.channel_url'),
        ]);
    })->name('gallery');

    Route::get('/contact', [ContactController::class, 'create'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.send');

    Route::middleware('throttle:20,1')->group(function () {
        Route::get('/verify-member', [VerificationController::class, 'index'])->name('verify-member');
        Route::get('/verify/{token}', [VerificationController::class, 'card'])
            ->where('token', '[A-Za-z0-9]{32}')
            ->name('verify-card');
    });
};

Route::middleware('locale:en')->group($publicRoutes);
Route::prefix('fa')->name('fa.')->middleware('locale:fa')->group($publicRoutes);

// Facebook Messenger: Meta verifies the URL with a GET, then POSTs each new message.
Route::get('/webhooks/messenger', [MessengerWebhookController::class, 'verify'])->name('webhooks.messenger.verify');
Route::post('/webhooks/messenger', [MessengerWebhookController::class, 'receive'])->name('webhooks.messenger');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account', [AuthController::class, 'account'])->name('account');
    Route::put('/account', [AuthController::class, 'updateAccount'])->name('account.update');

    Route::resource('activities', ActivityController::class)->except(['show']);
    Route::resource('news', NewsController::class)->except(['show']);
    Route::resource('galeries', GaleryController::class)->except(['show']);
    Route::resource('verifications', AdminVerificationController::class)->except(['show']);
    Route::get('verifications/{verification}/card', [MemberCardController::class, 'show'])->name('verifications.card');
    Route::get('verifications/{verification}/card/{side}.png', [MemberCardController::class, 'image'])
        ->whereIn('side', ['front', 'back'])
        ->name('verifications.card.image');
    Route::post('verifications/{verification}/card/replace', [MemberCardController::class, 'replace'])->name('verifications.card.replace');
    Route::post('verifications/{verification}/revoke', [AdminVerificationController::class, 'revoke'])->name('verifications.revoke');
    Route::post('verifications/{verification}/restore', [AdminVerificationController::class, 'restore'])->name('verifications.restore');
});
