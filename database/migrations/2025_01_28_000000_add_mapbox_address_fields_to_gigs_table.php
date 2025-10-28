<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->string('address')->nullable()->after('zip_id')->comment('Full address from Mapbox');
            $table->decimal('latitude', 10, 8)->nullable()->after('address')->comment('Latitude from Mapbox');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude')->comment('Longitude from Mapbox');
            $table->string('city')->nullable()->after('longitude')->comment('City name from Mapbox');
            $table->string('state')->nullable()->after('city')->comment('State name from Mapbox');
            $table->string('country')->nullable()->after('state')->comment('Country name from Mapbox');
            $table->string('postcode')->nullable()->after('country')->comment('Postal code from Mapbox');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'latitude',
                'longitude',
                'city',
                'state',
                'country',
                'postcode'
            ]);
        });
    }
};
