<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

// Public pages are served in English at / and in Dari at /fa.
class SetLocale
{
    public function handle(Request $request, Closure $next, string $locale): Response
    {
        app()->setLocale($locale);
        Carbon::setLocale($locale);

        if ($locale === 'fa') {
            // Carbon's "fa" month names are the Iranian ones; Afghanistan uses these.
            $months = ['جنوری', 'فبروری', 'مارچ', 'اپریل', 'می', 'جون', 'جولای', 'اگست', 'سپتمبر', 'اکتوبر', 'نومبر', 'دسمبر'];
            Carbon::getTranslator()->setTranslations(['months' => $months, 'months_short' => $months]);
        }

        return $next($request);
    }
}
