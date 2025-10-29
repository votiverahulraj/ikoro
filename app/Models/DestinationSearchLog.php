<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationSearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'state_id',
        'city_id',
        'zip_id',
        'location_type',
        'location_id',
        'full_location_name',
        'latitude',
        'longitude',
        'search_count',
        'last_searched_at',
    ];

    protected $casts = [
        'last_searched_at' => 'datetime',
    ];

    // Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function zipcode()
    {
        return $this->belongsTo(Zipcode::class, 'zip_id');
    }

    // Helper method to get top searched destinations
    public static function getTopDestinations($limit = 10)
    {
        return self::orderBy('search_count', 'desc')
            ->orderBy('last_searched_at', 'desc')
            ->take($limit)
            ->get();
    }

    // Helper method to log a search
    public static function logSearch($locationType, $locationId, $fullLocationName, $relatedIds = [])
    {
        $log = self::where('location_type', $locationType)
            ->where('location_id', $locationId)
            ->first();

        if ($log) {
            // Update existing record
            $log->increment('search_count');
            $log->update(['last_searched_at' => now()]);
        } else {
            // Create new record
            self::create([
                'location_type' => $locationType,
                'location_id' => $locationId,
                'full_location_name' => $fullLocationName,
                'country_id' => $relatedIds['country_id'] ?? null,
                'state_id' => $relatedIds['state_id'] ?? null,
                'city_id' => $relatedIds['city_id'] ?? null,
                'zip_id' => $relatedIds['zip_id'] ?? null,
                'latitude' => $relatedIds['latitude'] ?? null,
                'longitude' => $relatedIds['longitude'] ?? null,
                'search_count' => 1,
                'last_searched_at' => now(),
            ]);
        }
    }
}
