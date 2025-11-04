<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    public function switchCurrency(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|in:' . implode(',', getSupportedCurrencies()),
        ]);

        $currencyCode = $request->input('currency_code');

        if (Auth::check()) {
            $user = Auth::user();
            $user->currency_preference = $currencyCode;
            $user->save();
        } else {
            Session::put('currency_preference', $currencyCode);
        }

        return redirect()->back();
    }
}
