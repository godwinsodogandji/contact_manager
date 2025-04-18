<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class CountryService
{
    /**
     * Create a new class instance.
     */
    public function getCountries()
    {
        $response = Http::withoutVerifying()->get('https://restcountries.com/v3.1/all');
        $countries = $response->json();
    
        return collect($countries)->map(function ($country) {
            return [
                'id' => $country['cca2'], // Utiliser le code ISO alpha-2 comme ID
                'name' => $country['name']['common'],
                'flag' => $country['flags']['png'],
            ];
        })->sortBy('name')->values();
    }
}
