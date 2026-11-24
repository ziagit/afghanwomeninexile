<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Galery;
use App\Models\News;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'activityCount' => Activity::count(),
            'newsCount' => News::count(),
            'galeryCount' => Galery::count(),
            'recentActivities' => Activity::latest()->limit(3)->get(),
            'recentGaleries' => Galery::latest()->limit(3)->get(),
        ]);
    }
}
