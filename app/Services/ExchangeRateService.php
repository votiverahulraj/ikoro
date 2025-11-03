<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    private string $apiKey;
    private string $baseUrl;
    private int $cacheTtl;

    public function __construct()
    {
        $this->apiKey = config('currency.api_key');
        $this->baseUrl = config('currency.api_url');
        $this->cacheTtl = config('currency.cache_ttl', 3600);
    }

    /**
     * Get exchange rates for a base currency
     */
    public function getExchangeRates(string $baseCurrency = 'USD'): array
    {
        $cacheKey = "exchange_rates_{$baseCurrency}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($baseCurrency) {
            $response = Http::get("{$this->baseUrl}{$this->apiKey}/latest/{$baseCurrency}");

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch exchange rates: ' . $response->body());
            }

            $data = $response->json();

            if ($data['result'] !== 'success') {
                throw new \Exception('Exchange rate API error: ' . ($data['error-type'] ?? 'Unknown error'));
            }

            return $data['conversion_rates'];
        });
    }

    /**
     * Convert amount from one currency to another
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rates = $this->getExchangeRates($fromCurrency);

        if (!isset($rates[$toCurrency])) {
            throw new \Exception("Exchange rate for {$toCurrency} not available");
        }

        return round($amount * $rates[$toCurrency], 2);
    }

    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies(): array
    {
        return config('currency.supported_currencies', []);
    }

    /**
     * Check if currency is supported
     */
    public function isCurrencySupported(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->getSupportedCurrencies());
    }

    /**
     * Get real-time exchange rate between two currencies
     */
    public function getExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        $rates = $this->getExchangeRates($fromCurrency);

        if (!isset($rates[$toCurrency])) {
            throw new \Exception("Exchange rate for {$toCurrency} not available");
        }

        return $rates[$toCurrency];
    }

    /**
     * Clear exchange rate cache
     */
    public function clearCache(string $baseCurrency = null): void
    {
        if ($baseCurrency) {
            Cache::forget("exchange_rates_{$baseCurrency}");
        } else {
            // Clear all exchange rate caches
            $supportedCurrencies = $this->getSupportedCurrencies();
            foreach ($supportedCurrencies as $currency) {
                Cache::forget("exchange_rates_{$currency}");
            }
        }
    }
}