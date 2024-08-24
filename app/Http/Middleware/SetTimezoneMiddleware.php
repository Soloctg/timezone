<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use DateTimeZone;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTimezoneMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // This sets the default timezone for Carbon and PHP to the users timezone
            date_default_timezone_set(auth()->user()->timezone);
            // Here we are using php-intl extension to get users locale (at least trying to guess it!)
            $locale = new DateTimeZone(auth()->user()->timezone);
            $localeCode = $locale->getLocation()['country_code'] ?? 'en_US';
            // Making sure Carbon knows which locale we will work with
            Carbon::setLocale($localeCode);
        }

        return $next($request);
    }
}
