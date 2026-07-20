<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home', [
    'pageTitle' => null,
    'metaDescription' => 'MAWIE - The Movement of Afghanistan Women in Exile. Advancing human rights, gender equality, and the protection of Afghan women through peaceful advocacy, education, and international cooperation.',
    'featuredPosts' => config('mawie.featured_posts'),
])->name('home');

Route::view('/activities', 'pages.activities', [
    'pageTitle' => 'Activities',
    'metaDescription' => "A chronological record of MAWIE's public statements, commemorations, and engagements - a plainspoken account of what we've raised our voice on, and when.",
    'archiveEntries' => config('mawie.archive_entries'),
])->name('activities');

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
