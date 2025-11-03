<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class CurrencySelector extends Component
{
    public string $currentCurrency;
    public array $currencies;
    public bool $showFlag;

    public function __construct(bool $showFlag = true)
    {
        $this->currentCurrency = $this->getCurrentCurrency();
        $this->currencies = $this->getAvailableCurrencies();
        $this->showFlag = $showFlag;
    }

    private function getCurrentCurrency(): string
    {
        if (Auth::check()) {
            return Auth::user()->currency_preference ?? 'USD';
        }
        
        return session('currency', 'USD');
    }

    private function getAvailableCurrencies(): array
    {
        return [
            'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'flag' => '🇺🇸'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€', 'flag' => '🇪🇺'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'flag' => '🇬🇧'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥', 'flag' => '🇯🇵'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$', 'flag' => '🇨🇦'],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$', 'flag' => '🇦🇺'],
            'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF', 'flag' => '🇨🇭'],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥', 'flag' => '🇨🇳'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹', 'flag' => '🇮🇳'],
            'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦', 'flag' => '🇳🇬'],
            'GHS' => ['name' => 'Ghanaian Cedi', 'symbol' => '₵', 'flag' => '🇬🇭'],
            'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'flag' => '🇰🇪'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R', 'flag' => '🇿🇦'],
            'EGP' => ['name' => 'Egyptian Pound', 'symbol' => 'E£', 'flag' => '🇪🇬'],
            'MAD' => ['name' => 'Moroccan Dirham', 'symbol' => 'DH', 'flag' => '🇲🇦'],
        ];
    }

    public function render()
    {
        return view('components.currency-selector');
    }
}