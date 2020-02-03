<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($locale = $request->session()->get('locale', $this->getLocaleFromHeader($request))){
            app()->setLocale($locale);
            $request->session()->put('locale', $locale);
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    private function getLocaleFromHeader(Request $request): ?string
    {
        if(preg_match('/^([a-z]{2,})/i', $request->header('Accept-Language'), $matches)){
            return mb_strtolower($matches[1]);
        }

        return null;
    }
}
