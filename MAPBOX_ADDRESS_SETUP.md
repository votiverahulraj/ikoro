# Mapbox Address Fields - Setup Instructions

## Summary
This setup adds Mapbox Address Autofill fields to your gigs table so you can save complete address information including coordinates.

## What Was Modified

### 1. Database Migration Created
**File:** `database/migrations/2025_01_28_000000_add_mapbox_address_fields_to_gigs_table.php`

This migration adds the following columns to the `gigs` table:
- `address` (string) - Full address from Mapbox
- `latitude` (decimal) - Latitude coordinates
- `longitude` (decimal) - Longitude coordinates
- `city` (string) - City name from Mapbox
- `state` (string) - State/Province name from Mapbox
- `country` (string) - Country name from Mapbox
- `postcode` (string) - Postal/ZIP code from Mapbox

### 2. Controller Updated
**File:** `app/Http/Controllers/GigController.php`

**Changes:**
- Added validation for the new Mapbox fields in the `store()` method
- Added field mapping to convert form field names to database column names:
  - `address-line1` → `address`
  - `address-level2` → `city`
  - `address-level1` → `state`
  - `latitude`, `longitude`, `country`, `postcode` remain the same

### 3. Blade View Updated
**File:** `resources/views/host/gig/addedit.blade.php`

**Changes:**
- Added `value` attributes to all address input fields
- Now displays saved values when editing an existing gig
- Shows old input values if validation fails

## Installation Steps

### Step 1: Run the Migration
```bash
php artisan migrate
```

This will add the new columns to your `gigs` table.

### Step 2: Test the Form
1. Create a new gig
2. Use the Mapbox address autocomplete to select an address
3. Fill in all other required fields
4. Submit the form
5. The address fields will now be saved to the database

### Step 3: Verify Data
After creating/updating a gig, you can verify the data was saved by checking:
- The database directly in phpMyAdmin or your database tool
- Look at the `gigs` table and check the new columns

## How It Works

### Creating a Gig:
1. User types an address in the Mapbox autocomplete field
2. User selects an address from suggestions
3. Mapbox JavaScript automatically fills in:
   - Address line 1
   - City
   - State
   - Country
   - Postal code
   - Latitude & longitude (hidden fields)
4. On form submission, these values are validated and saved

### Editing a Gig:
1. When loading the edit form, saved address values are populated
2. User can update the address using Mapbox autocomplete
3. Updated values are saved to the database

## Data Structure

The form now saves BOTH:
1. **Mapbox text fields** (new):
   - Full address string
   - City, state, country names
   - Postal code
   - Coordinates (lat/lng)

2. **Existing foreign keys** (unchanged):
   - `country_id`, `state_id`, `city_id`, `zip_id`
   - These link to your existing country/state/city/zipcode tables

This dual approach allows you to:
- Have the exact address the user entered via Mapbox
- Still maintain relationships with your location tables
- Use coordinates for map features

## Notes
- All new fields are nullable, so existing gigs won't break
- The existing code remains unchanged - we only added new functionality
- The model uses `protected $guarded = []`, so new fields are automatically fillable
