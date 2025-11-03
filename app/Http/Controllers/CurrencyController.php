<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    private ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Switch currency preference
     */
    public function switch(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|size:3|in:' . implode(',', $this->exchangeRateService->getSupportedCurrencies())
        ]);

        $currency = strtoupper($request->currency);

        if (Auth::check()) {
            // Save to user profile
            Auth::user()->setPreferredCurrency($currency);
        } else {
            // Save to session
            session(['currency' => $currency]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'currency' => $currency,
                'message' => 'Currency updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Currency updated successfully');
    }

    /**
     * Get current exchange rates
     */
    public function getRates(Request $request)
    {
        $baseCurrency = $request->get('base', 'USD');
        
        try {
            $rates = $this->exchangeRateService->getExchangeRates($baseCurrency);
            
            return response()->json([
                'success' => true,
                'base' => $baseCurrency,
                'rates' => $rates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert amount
     */
    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3'
        ]);

        try {
            $converted = $this->exchangeRateService->convert(
                $request->amount,
                strtoupper($request->from),
                strtoupper($request->to)
            );

            return response()->json([
                'success' => true,
                'original' => [
                    'amount' => $request->amount,
                    'currency' => strtoupper($request->from)
                ],
                'converted' => [
                    'amount' => $converted,
                    'currency' => strtoupper($request->to)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's current currency preference
     */
    public function getCurrentCurrency()
    {
        $currency = 'USD';

        if (Auth::check()) {
            $currency = Auth::user()->getPreferredCurrency();
        } else {
            $currency = session('currency', 'USD');
        }

        return response()->json([
            'currency' => $currency
        ]);
    }
}