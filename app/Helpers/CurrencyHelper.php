<?php

if (!function_exists('convertCurrency')) {
    /**
     * Convert amount from one currency to another
     */
    function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $service = app(\App\Services\ExchangeRateService::class);
        return $service->convert($amount, $fromCurrency, $toCurrency);
    }
}

if (!function_exists('getExchangeRate')) {
    /**
     * Get exchange rate between two currencies
     */
    function getExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        $service = app(\App\Services\ExchangeRateService::class);
        return $service->getExchangeRate($fromCurrency, $toCurrency);
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format amount with currency symbol
     */
    function formatCurrency(float $amount, string $currency = 'USD'): string
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'NGN' => '₦',
            'GHS' => '₵',
            'KES' => 'KSh',
            'ZAR' => 'R',
            'EGP' => 'E£',
            'MAD' => 'DH',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'CHF' => 'CHF',
            'CNY' => '¥',
            'INR' => '₹'
        ];

        $symbol = $symbols[$currency] ?? $currency;
        $decimals = in_array($currency, ['JPY', 'KES']) ? 0 : 2;

        return $symbol . number_format($amount, $decimals);
    }
}

if (!function_exists('convertAndFormatCurrency')) {
    /**
     * Convert and format currency in one step
     */
    function convertAndFormatCurrency(float $amount, string $fromCurrency, string $toCurrency): string
    {
        $converted = convertCurrency($amount, $fromCurrency, $toCurrency);
        return formatCurrency($converted, $toCurrency);
    }
}

if (!function_exists('getSupportedCurrencies')) {
    /**
     * Get list of supported currencies
     */
    function getSupportedCurrencies(): array
    {
        return config('currency.supported_currencies', []);
    }
}

if (!function_exists('isCurrencySupported')) {
    /**
     * Check if currency is supported
     */
    function isCurrencySupported(string $currency): bool
    {
        $service = app(\App\Services\ExchangeRateService::class);
        return $service->isCurrencySupported($currency);
    }
}