<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class SetUserCurrency
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $currency = $this->getUserCurrency();
        
        // Share currency with all views
        View::share('userCurrency', $currency);
        
        // Store in session for easy access
        session(['user_currency' => $currency]);
        
        return $next($request);
    }

    /**
     * Get user's preferred currency
     */
    private function getUserCurrency(): string
    {
        // Priority: Authenticated user preference > Session > Default
        if (Auth::check()) {
            return Auth::user()->getPreferredCurrency();
        }
        
        return session('currency', config('currency.default', 'USD'));
    }
}