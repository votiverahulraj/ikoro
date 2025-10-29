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
        Schema::create('destination_search_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('cascade');
            $table->foreignId('zip_id')->nullable()->constrained('zipcodes')->onDelete('cascade');
            $table->string('location_type'); // 'Country', 'State', 'City', 'Zipcode'
            $table->integer('location_id'); // The ID of the location
            $table->string('full_location_name'); // "Nigeria, Lagos, Ikeja, 100001"
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('search_count')->default(1);
            $table->timestamp('last_searched_at');
            $table->timestamps();

            // Index for faster queries
            $table->index(['location_type', 'location_id']);
            $table->index('search_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_search_logs');
    }
};
