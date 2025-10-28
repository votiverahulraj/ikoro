{{-- @extends('host.layouts.app') --}}



@extends('host.layout.layout')



@section('title', isset($gig['id']) ? 'Edit Gigs' : 'Create Gigs')

@section('content')

<!-- Mapbox -->

<!-- <link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet">

<script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>

<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>

<link

    rel="stylesheet"

    href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" -->

/>

<link href="https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.min.css" rel="stylesheet">

<script id="search-js" defer="" src="https://api.mapbox.com/search-js/v1.5.0/web.js"></script>



    <style>

        .add-edit-content .add_more {

            width: 85%;

            padding: 8px 12px;

            font-size: 14px;

            border-radius: 5px;

            font-weight: bold;

        }



        button.btn.btn-primary.float-right {

            width: 13%;

            border-radius: 5px;

        }



        button.remove_gig_media_offer.btn.btn-sm.btn-danger {

            width: 40%;

            padding: 8px;

            border-radius: 5px;

            background-color: #dc3545;

            border: none;

            transition: background-color 0.3s ease;

            margin-top: -27px;

        }



        button.remove.btn.btn-sm.btn-danger {

            width: 40%;

            padding: 8px;

            border-radius: 5px;

            background-color: #dc3545;

            border: none;

            transition: background-color 0.3s ease;

            margin-top: -27px;

        }



        button.remove_gig_media_offer.btn.btn-sm.btn-danger:hover {

            background-color: #ff1c32;

        }



        .add-edit-content h4.features-text {

            padding-top: 10px;

            padding-bottom: 10px;

        }



        .add-edit-content .remove:hover {

            background-color: #c82333;

        }



        .add-edit-content .form-control {

            border-radius: 6px;

            font-size: 14px;

            height: 41px;

        }



        .add-edit-content label {

            font-weight: 600;

            font-size: 14px;

            margin-bottom: 5px;

        }



        aside.main-sidebar.sidebar-dark-primary.elevation-4 img.brand-image.img-circle.elevation-3 {

            float: left;

            line-height: .8;

            margin-left: .8rem;

            margin-right: .5rem;

            margin-top: -3px;

            width: 40px !important;

            max-height: 40px !important;

            height: 40px !important;

            object-fit: cover !important;

        }



        .add-edit-content .mt-2 img {

            border-radius: 8px;

            border: 1px solid #ddd;

            padding: 4px;

            background: #fff;

            width: 65%;

            height: 135px;

            object-fit: cover;

        }



        .add-edit-content .form-row {

            padding: 15px;

            margin-bottom: 15px;

            border: 1px solid #e0e0e0;

            border-radius: 10px;

            background: #f9f9f9;

            width: 100%;

            padding-bottom: 10px;

        }



        .add-edit-content .form-group.col-md-2.remove-add-btn {

            margin-top: 24px;

        }



        .add-edit-content .features-show-img {

            padding-bottom: 25px;

        }



        .add-edit-content button.remove.btn.btn-sm.btn-danger {

            margin-top: 0;

        }



        div#html_to button.remove.btn.btn-sm.btn-danger {

            margin-top: -3px;

        }



        p.alert.alert-success {

            text-align: center;

            width: 1080px;

            margin: auto;

            margin-bottom: 15px;

        }

    </style>



    <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <div class="content-header">

            <div class="container">

                <div class="row mb-2">

                    <div class="col-sm-6">

                        <h1 class="m-0">{{ isset($gig['id']) ? 'Edit Service' : 'Create Service' }}</h1>

                    </div>

                    <div class="col-sm-6">

                        <ol class="breadcrumb float-sm-right">

                            <li class="breadcrumb-item"><a href="{{ route('host.dashboard') }}">Home</a></li>

                            <li class="breadcrumb-item active">{{ isset($gig['id']) ? 'Edit Service' : 'Create Service' }}</li>

                        </ol>

                    </div>

                </div>

            </div>

        </div>



        @if (Session::has('message'))

            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>

        @endif

        <form id="gigForm" method="POST" action="{{ route('host.gig.store') }}" enctype="multipart/form-data">

            @csrf

            <div class="container-fluid">

                <div class="card">

                    <div class="card-body">

                        <input type="hidden" name="gig_id" value="{{ $gig['id'] ?? '' }}">

                        <div class="form-row">

                            <div class="form-group col-md-6">

                                <label for="task_id">Type</label>

                                <select class="form-control" id="task_id" name="task_id" required>

                                    <option value="" disabled selected>Select type</option>

                                    @foreach ($tasks as $row)

                                        <option value="{{ $row->id }}"

                                            {{ ($gig['task_id'] ?? '') == $row->id ? 'selected' : '' }}>

                                            {{ $row->title }}

                                        </option>

                                    @endforeach

                                </select>

                                <x-input-error :messages="$errors->get('type')" class="mt-2" />

                            </div>



                            {{-- <div class="form-group col-md-3">

                                <x-input-label for="title" :value="__('Title')" />

                                <x-text-input type="text" class="form-control" id="title" name="title"

                                    :value="$gig['title'] ?? old('title')" required />

                                <x-input-error :messages="$errors->get('title')" class="mt-2" />

                            </div> --}}

                            <input type="hidden" id="title" name="title" value="blank" />



                            <div class="form-group col-md-6">

                                {{-- <label for="equipment_price_id">Equipment Used</label>

                                <select name="equipment_price_id" id="equipment_price_id" class="form-control">

                                    <option value="">Select Equipment Used</option>

                                    @foreach ($equipment_price_all as $row)

                                        <option value="{{ $row->id }}" price="{{ $row->price }}"

                                            minutes="{{ $row->duration_minutes }}" equipment_id="{{ $row->equipment_id }}"

                                            equipment_name = "{{ $row->equipment->name }}"

                                            {{ ($gig['equipment_price_id'] ?? '') == $row->id ? 'selected' : '' }}>

                                            {{ $row->equipment->name }}

                                        </option>

                                    @endforeach

                                </select> --}}

                                <x-input-label for="equipment_name" :value="__('Tool')" />

                                <x-text-input type="text" class="form-control" id="equipment_name" name="equipment_name"

                                    :value="$gig['equipment_name'] ?? old('equipment_name')" required />

                                <x-input-error :messages="$errors->get('equipment_name')" class="mt-2" />

                            </div>













                            <!-- remove it if you want to uncomment the below one  -->

                            {{-- <input type="hidden" id="price" name="price" value="{{ $gig['price'] ?? '' }}" />

                            <input type="hidden" id="minutes" name="minutes" value="{{ $gig['minutes'] ?? '' }}" />

                            <input type="hidden" id="eq_id" name="equipment_id"

                                value="{{ $gig['equipment_id'] ?? '' }}" />

                            <input type="hidden" id="equipment_name" name="equipment_name"

                                value="{{ $gig['equipment_name'] ?? '' }}" /> --}}



                            <!-- remove it if you want to uncomment the below one  -->



                            <!--

                            <div class="form-group col-md-3">

                                <label for="price">Price Details </label>



                                @php

                                    $price_str = '';



                                    if (isset($gig->price) && isset($gig->minutes)) {

                                        $price_str = $gig->price . ' per ' . $gig->minutes . ' minutes';

                                    }

                                @endphp



                                <input readonly type="text" class="form-control" id="pricing"

                                    value="{{ $price_str }}" />

                                <input type="hidden" id="equipment_name" name="equipment_name"

                                    value="{{ $gig['equipment_name'] ?? '' }}" />

                                <input type="hidden" id="price" name="price" value="{{ $gig['price'] ?? '' }}" />

                                <input type="hidden" id="minutes" name="minutes" value="{{ $gig['minutes'] ?? '' }}" />

                                <input type="hidden" id="eq_id" name="equipment_id" value="{{ $gig['equipment_id'] ?? '' }}" />

                            </div>

                        -->

                        </div>





                        <div class="form-row">

                            <div class="form-group col-md-3">

                                <x-input-label for="price30min" :value="__('Enter Price for 30 Mins :')" />

                                <x-text-input type="number" step="1" min="0"

                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control"

                                    id="price30min" name="price30min" :value="$gig['price30min'] ?? old('price30min')" required />

                                <x-input-error :messages="$errors->get('price30min')" class="mt-2" />

                            </div>

                            <div class="form-group col-md-3">

                                <x-input-label for="price60min" :value="__('Enter Price for 60 Mins :')" />

                                <x-text-input type="number" step="1" min="0"

                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control"

                                    id="price60min" name="price60min" :value="$gig['price60min'] ?? old('price60min')" required />

                                <x-input-error :messages="$errors->get('price60min')" class="mt-2" />

                            </div>

                            <div class="form-group col-md-3">

                                <x-input-label for="price90min" :value="__('Enter Price for 90 Mins :')" />

                                <x-text-input type="number" step="1" min="0"

                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control"

                                    id="price90min" name="price90min" :value="$gig['price90min'] ?? old('price90min')" required />

                                <x-input-error :messages="$errors->get('price90min')" class="mt-2" />

                            </div>

                            <div class="form-group col-md-3">

                                <x-input-label for="price120min" :value="__('Enter Price for 120 Mins :')" />

                                <x-text-input type="number" step="1" min="0"

                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control"

                                    id="price120min" name="price120min" :value="$gig['price120min'] ?? old('price120min')" required />

                                <x-input-error :messages="$errors->get('price120min')" class="mt-2" />

                            </div>

                        </div>


                        <div class="form-row">

                            <div class="form-group col-md-12">
                                <x-input-label for="address" :value="__('address')" />

                                <input id="address-line1" class="input mb12" autocomplete="address-line1" name="address-line1" value="{{ $gig['address'] ?? old('address-line1') }}" required="">

                                <input type="hidden" name="latitude" id="latitude" value="{{ $gig['latitude'] ?? old('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ $gig['longitude'] ?? old('longitude') }}">

                            </div>

                        </div>


                        <div class="form-row">

                            <div class="form-group col-md-3">

                                <x-input-label for="city" :value="__('City')" />

                                <input class="input mb12" autocomplete="address-level2" name="address-level2" value="{{ $gig['city'] ?? old('address-level2') }}" required="">

                            </div>

                            <div class="form-group col-md-3">

                                <x-input-label for="state" :value="__('State')" />

                                <input class="input mb12" autocomplete="address-level1" name="address-level1" value="{{ $gig['state'] ?? old('address-level1') }}" required="">

                            </div>

                            <div class="form-group col-md-3">

                                <x-input-label for="country" :value="__('Country')" />

                                <input class="input" autocomplete="country-name" name="country" value="{{ $gig['country'] ?? old('country') }}" required="">

                            </div>

                            <div class="form-group col-md-3">

                                <x-input-label for="zipcode" :value="__('Zip Code')" />

                                <input class="input" autocomplete="postal-code" name="postcode" id="postcode" value="{{ $gig['postcode'] ?? old('postcode') }}" required="">

                            </div>

                        </div>



                        <div class="form-row">

                            <div class="form-group col-md-3">

                                <label for="country_id">Country</label>

                                <select name="country_id" id="country_id" class="form-control select2">

                                    <option value="">Select Country</option>

                                    @foreach ($country as $val)

                                        <option value="{{ $val->id }}"

                                            {{ ($gig['country_id'] ?? '') == $val->id ? 'selected' : '' }}>

                                            {{ $val->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <x-input-error :messages="$errors->get('country_id')" class="mt-2" />

                            </div>

                            <div class="form-group col-md-3">

                                <label for="state_id">State</label>

                                <select name="state_id" id="state_id" class="form-control select2">

                                    <option value="">Select State</option>

                                </select>

                                <x-input-error :messages="$errors->get('state_id')" class="mt-2" />

                            </div>

                            <div class="form-group col-md-3">

                                <label for="city_id">City</label>

                                <select name="city_id" id="city_id" class="form-control select2">

                                    <option value="">Select City</option>

                                </select>

                                <x-input-error :messages="$errors->get('city_id')" class="mt-2" />

                            </div>

                            <div class="form-group col-md-3">

                                <label for="zip_id">ZIP CODE</label>

                                <select name="zip_id" id="zip_id" class="form-control select2">

                                    <option value="">Select ZipCode</option>

                                </select>

                                <x-input-error :messages="$errors->get('zipcode')" class="mt-2" />

                            </div>

                        </div>



                        <div class="form-row">

                            <div class="form-group col-md">

                                <x-input-label for="description" :value="__('Description')" />

                                <textarea name="description" id="description" rows="3" class="form-control">{{ $gig['description'] ?? old('description') }}</textarea>

                                <x-input-error :messages="$errors->get('description')" class="mt-2" />

                            </div>

                        </div>



                    </div>

                    <div class="card-footer add-edit-content">



                        <div class="mb-3">

                            <!-- <h4>Features</h4> -->

                            <h4 class="features-text">Offers</h4>

                        </div>



                        <div class="row features-show-img">

                            <div class="col-md-2 features-show-left">

                                <button type="button" class="add_more btn btn-sm btn-primary">

                                    Add Offers

                                </button>

                            </div>

                            <div class="col-md-10 features-show-right">

                                <!-- @if (isset($gig['features']))

                                    @foreach ($gig['features'] as $feature)

    <div class="form-row row">

                                            <div class="form-group col-md-5">

                                                <label>Label</label>

                                                <input type="text" class="form-control" name="features[label][]"

                                                    value="{{ $feature['label'] }}" required />

                                                <x-input-error :messages="$errors->get('feat')" class="mt-2" />

                                            </div>

                                            <div class="form-group col-md-5">

                                                <label>Value</label>

                                                <input type="text" class="form-control" name="features[value][]"

                                                    value="{{ $feature['value'] }}" required />

                                                <x-input-error :messages="$errors->get('val')" class="mt-2" />

                                            </div>

                                            <div class="form-group col-md-2">

                                                <br />

                                                <button type="button" class="remove btn btn-sm btn-danger"

                                                    feature_id="{{ $feature['id'] }}" data-toggle="modal" feature_id="">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </div>

                                        </div>

    @endforeach

                                @endif -->



                                @if (isset($gig['features']))

                                    @foreach ($gig['features'] as $feature)

                                        <div class="form-row row">

                                            <div class="form-group col-md-5">

                                                <label>Label</label>

                                                <input type="text" class="form-control" name="features[label][]"

                                                    value="{{ $feature['label'] }}" required />

                                                <x-input-error :messages="$errors->get('features.label')" class="mt-2" />

                                            </div>



                                            <div class="form-group col-md-5">

                                                <label>Image</label>



                                                {{-- File input for uploading a new image --}}

                                                <input type="file" class="form-control" name="features[value][]"

                                                    @if (empty($feature['value'])) required @endif />



                                                {{-- Hidden input to keep the old image path if no new image uploaded --}}

                                                @if (!empty($feature['value']))

                                                    <input type="hidden" name="features[old_value][]"

                                                        value="{{ $feature['value'] }}">



                                                    {{-- Preview the uploaded image --}}

                                                    <div class="mt-2">

                                                        <img src="{{ asset($feature['value']) }}" alt="Feature Image">

                                                    </div>

                                                @else

                                                    {{-- Empty hidden field if no image yet --}}

                                                    <input type="hidden" name="features[old_value][]" value="">

                                                @endif



                                                <x-input-error :messages="$errors->get('features.value')" class="mt-2" />

                                            </div>

                                            <!--

                                            <div class="form-group col-md-2">

                                                <br />

                                                <button type="button" class="remove btn btn-sm btn-danger"

                                                    feature_id="{{ $feature['id'] }}" data-toggle="modal">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </div>

                                            -->

                                            <div class="form-group col-md-2 remove-add-btn">

                                                <br />

                                                <button type="button"

                                                    class="remove_gig_media_offer btn btn-sm btn-danger"

                                                    route="{{ route('host.gig.deleteMediaOffer', $feature['id']) }}"

                                                    media_id="{{ $feature['id'] }}">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </div>



                                        </div>

                                    @endforeach

                                @endif

                                <div id="html_to">



                                </div>

                            </div>

                        </div>

                        <button type="submit"

                            class="btn btn-primary float-right">{{ isset($gig['id']) ? 'Update' : 'Create' }}</button>

                    </div>

                </div>

        </form>



    </div>

    </div>

    <script>

        let host = @json($gig ?? []);

        // console.log("==========", host.country.name);

        // console.log("==========", host.state.name);

        // console.log("==========", host.city.name);

        // console.log("==========", host.zip.code);

    </script>



    <!-- <script>

        const ACCESS_TOKEN = 'pk.eyJ1IjoiaWtvcm9ocSIsImEiOiJjbWdkc2tkcGYxbWJoMmpxdzV5dm10cjhhIn0.TpAnavdsPjHbTMD1N1OsEw';

        window.addEventListener('load', () => {

            const collection = mapboxsearch.autofill({

                accessToken: ACCESS_TOKEN

            });

        });

    </script> -->

    <script>
const MAPBOX_ACCESS_TOKEN = 'pk.eyJ1IjoiaWtvcm9ocSIsImEiOiJjbWdkc2tkcGYxbWJoMmpxdzV5dm10cjhhIn0.TpAnavdsPjHbTMD1N1OsEw';
// const MAPBOX_ACCESS_TOKEN = 'pk.eyJ1IjoiaWtvcm9ocSIsImEiOiJ...';
// mapboxsearch.config.accessToken = MAPBOX_ACCESS_TOKEN;

let autofillCollection;
let minimap;

function showMap() {
  const el = document.getElementById("minimap-container");
  if (el) el.classList.remove("none");
}

// start up
window.addEventListener('load', async () => {
  console.log('[map] window loaded');

  // safety: make sure mapboxsearch is available
  if (typeof mapboxsearch === 'undefined') {
    console.error('[map] mapboxsearch is undefined — make sure Mapbox Address Autofill script is loaded BEFORE this script');
    return;
  }

  mapboxsearch.config.accessToken = MAPBOX_ACCESS_TOKEN;
  console.log('[map] accessToken set');

  // init autofill — capture returned object
  try {
    autofillCollection = mapboxsearch.autofill({});
    console.log('[map] autofillCollection created:', autofillCollection);
  } catch (err) {
    console.error('[map] error creating autofillCollection:', err);
    return;
  }

  // init minimap
  try {
    minimap = new MapboxAddressMinimap();
    minimap.canAdjustMarker = true;
    minimap.satelliteToggle = true;

    minimap.onSaveMarkerLocation = (lnglat) => {
      console.log('[minimap] onSaveMarkerLocation fired:', lnglat);
      if (lnglat && typeof lnglat.lat === 'number' && typeof lnglat.lng === 'number') {
        document.getElementById('latitude').value = lnglat.lat;
        document.getElementById('longitude').value = lnglat.lng;
      }
    };

    const minimapContainer = document.getElementById("minimap-container");
    if (minimapContainer) {
      minimapContainer.appendChild(minimap);
      console.log('[minimap] appended to container');
    } else {
      console.warn('[minimap] container #minimap-container not found in DOM');
    }
  } catch (err) {
    console.error('[minimap] error initializing minimap:', err);
  }

  // ------------- DEBUGGING: make sure event is being attached ----------------
  if (!autofillCollection || typeof autofillCollection.addEventListener !== 'function') {
    console.error('[map] autofillCollection is invalid or has no addEventListener');
  } else {
    console.log('[map] attaching "retrieve" listener to autofillCollection');
    autofillCollection.addEventListener('retrieve', async (e) => {
      console.log('[map] retrieve event fired, event object:', e);

      // defensive: ensure e.detail and features exist
      const features = e && e.detail && e.detail.features;
      if (!features || !Array.isArray(features) || features.length === 0) {
        console.warn('[map] retrieve event had no features:', e);
        return;
      }

      const feature = features[0];
      console.log('[map] feature:', feature);

      // if minimap supports .feature property, set it. otherwise try to set center/marker manually.
      try {
        minimap.feature = feature;
        console.log('[minimap] minimap.feature set');
      } catch (err) {
        console.warn('[minimap] could not set minimap.feature, error:', err);
      }

      // coordinates: GeoJSON order is [lng, lat]
      if (feature.geometry && Array.isArray(feature.geometry.coordinates)) {
        const [lng, lat] = feature.geometry.coordinates;
        console.log('[map] coords extracted from feature:', { lat, lng });

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        if (latInput && lngInput) {
          latInput.value = lat;
          lngInput.value = lng;
          console.log('[map] hidden inputs updated:', latInput.value, lngInput.value);
        } else {
          console.warn('[map] latitude/longitude inputs not found. Make sure <input id="latitude"> and <input id="longitude"> exist in the DOM');
        }
      } else {
        console.warn('[map] feature.geometry.coordinates missing; feature:', feature);
      }

      // Populate the address field with the full address
      const addressInput = document.getElementById('address-line1');
      if (addressInput) {
        // Build full address from available fields
        const cityInput = document.getElementById('address-level2');
        const stateInput = document.getElementById('address-level1');
        const countryInput = document.getElementById('country');
        const postcodeInput = document.getElementById('postcode');

        // Wait a bit for Mapbox to populate other fields, then construct address
        setTimeout(() => {
          const addressParts = [];
          if (cityInput && cityInput.value) addressParts.push(cityInput.value);
          if (stateInput && stateInput.value) addressParts.push(stateInput.value);
          if (postcodeInput && postcodeInput.value) addressParts.push(postcodeInput.value);
          if (countryInput && countryInput.value) addressParts.push(countryInput.value);

          const constructedAddress = addressParts.join(', ');

          // If address input is empty, fill it with constructed address
          if (!addressInput.value && constructedAddress) {
            addressInput.value = constructedAddress;
            console.log('[map] address field populated with constructed address:', constructedAddress);
          } else {
            console.log('[map] address field already has value:', addressInput.value);
          }
        }, 500);
      }
    });
  }

  // Extra: listen for "select" on the input if retrieve doesn't fire in your environment
  const addrInput = document.querySelector('input[name="address-line1"], input[name="address"]');
  if (addrInput) {
    addrInput.addEventListener('change', () => {
      console.log('[fallback] address input "change" fired — trying to read coordinates from minimap.feature');
      try {
        const feat = minimap && minimap.feature;
        if (feat && feat.geometry && Array.isArray(feat.geometry.coordinates)) {
          const [lng, lat] = feat.geometry.coordinates;
          document.getElementById('latitude').value = lat;
          document.getElementById('longitude').value = lng;
          console.log('[fallback] set coords from minimap.feature:', lat, lng);
        } else {
          console.warn('[fallback] minimap.feature not available on address change');
        }
      } catch (err) {
        console.error('[fallback] error reading minimap.feature:', err);
      }
    });
  } else {
    console.warn('[fallback] address input not found for fallback listener');
  }

});
</script>

<!-- <script>
const ACCESS_TOKEN = 'pk.eyJ1IjoiaWtvcm9ocSIsImEiOiJjbWdkc2tkcGYxbWJoMmpxdzV5dm10cjhhIn0.TpAnavdsPjHbTMD1N1OsEw';

let autofillCollection;
let minimap;

// ✅ Show minimap when address selected
function showMap() {
    const el = document.getElementById("minimap-container");
    el.classList.remove("none");
}

window.addEventListener('load', function () {
    mapboxsearch.config.accessToken = ACCESS_TOKEN;

    // Initialize autofill
    autofillCollection = mapboxsearch.autofill({});

    // Initialize minimap
    minimap = new MapboxAddressMinimap();
    minimap.canAdjustMarker = true;
    minimap.satelliteToggle = true;

    // ✅ Update hidden fields when marker is moved manually
    minimap.onSaveMarkerLocation = (lnglat) => {
        console.log("Marker moved to:", lnglat);
        document.getElementById('latitude').value = lnglat.lat;
        document.getElementById('longitude').value = lnglat.lng;
    };

    // Append minimap
    const minimapContainer = document.getElementById("minimap-container");
    minimapContainer.appendChild(minimap);

    // ✅ When user selects an address, show map and set coords
    autofillCollection.addEventListener("retrieve", async (e) => {
        if (minimap) {
            const feature = e.detail.features[0];
            minimap.feature = feature;
            showMap();

            // Extract coordinates and save them
            const [lng, lat] = feature.geometry.coordinates;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            console.log("Selected address coords:", lat, lng);
        }
    });
});
</script> -->


    <!-- <script>

        const ACCESS_TOKEN = 'pk.eyJ1IjoiaWtvcm9ocSIsImEiOiJjbWdkc2tkcGYxbWJoMmpxdzV5dm10cjhhIn0.TpAnavdsPjHbTMD1N1OsEw';



        let autofillCollection;

        let minimap;



        // show the minimap

        // function showMap() {

        //     const el = document.getElementById("minimap-container");

        //     el.classList.remove("none");

        // }



        // function showConfirmPage() {

        //     document.querySelector('.address-page').classList.add('none')

        //     document.querySelector('.confirm-page').classList.remove('none')

        // }



        // function showAddressPage() {

        //     document.querySelector('.address-page').classList.remove('none')

        //     document.querySelector('.confirm-page').classList.add('none')

        // }



        function showSubmittedPage() {

        //     document.querySelector('.submit-order-button').classList.add('none')

        //     document.querySelector('.confirm-order-blurb').classList.add('none')

        //     document.querySelector('.change-address-button').classList.add('none')

        //     document.querySelector('.order-submitted-blurb').classList.remove('none')

        // }



        function restartExample() {

            window.location.reload();

        }



        // build out HTML to display the shipping address as formatted text

        // function buildAddressHTML(formData) {

        //     let addressHTML = `${formData.get('first-name')} ${formData.get('last-name')}<br/>`

        //     addressHTML += `${formData.get('address-line1 address-search')}<br/>`

        //     if (formData.get('address-line2')) addressHTML += `${formData.get('address-line2')}<br/>`

        //     addressHTML += `${formData.get('address-level2')} ${formData.get('address-level1')} ${formData.get('postal-code')}`

        //     return addressHTML

        // }



        // click listener for for the "Change" link

        // document.querySelector('.change-address-button').addEventListener('click', showAddressPage)



        // // click listener for for the "Submit Order" button

        // document.querySelector('.submit-order-button').addEventListener('click', showSubmittedPage)



        // // click listener for for the "Try this example again" link

        // document.querySelector('.restart-button').addEventListener('click', restartExample)





        // Autofill initialization

        window.addEventListener('load', function () {

            mapboxsearch.config.accessToken = ACCESS_TOKEN;



            // autofill() automatically binds address search to the address-line1 input

            autofillCollection = mapboxsearch.autofill({});



            // initialize a minimap

            minimap = new MapboxAddressMinimap();

            minimap.canAdjustMarker = true;

            minimap.satelliteToggle = true;

            // minimap.onSaveMarkerLocation = (lnglat) => {

            console.log(`Marker moved to ${lnglat}`);
            document.getElementById('latitude').value = lnglat.lat;
            document.getElementById('longitude').value = lnglat.lng;

            // };



            // append the minimap to the page

            const minimapContainer = document.getElementById("minimap-container");

            minimapContainer.appendChild(minimap);



            // when the user selects a suggested address, show the minimap

            autofillCollection.addEventListener(

                "retrieve",

                async (e) => {

                    if (minimap) {

                        minimap.feature = e.detail.features[0];

                        showMap();

                    }

                }

            );





            // when the form is submitted, use confirmAddress() to confirm the address

            const form = document.querySelector("form");

            form.addEventListener("submit", async (e) => {

                e.preventDefault();



                const result = await mapboxsearch.confirmAddress(form, {

                    minimap: true,

                    skipConfirmModal: (feature) => {

                        return ['exact'].includes(

                            feature.properties.match_code.confidence

                        )

                    }



                });



                // if no change is suggested, the address is confirmed. continue to the confirmation page

                if (result.type === 'nochange') {

                    const formData = new FormData(e.target)

                    // populate the form data to the confirmation page

                    document.getElementById('shipping-address').innerHTML = buildAddressHTML(formData)

                    showConfirmPage();

                }

            });

        })

    </script> -->



    <!-- <script>

        mapboxgl.accessToken = 'pk.eyJ1IjoiaWtvcm9ocSIsImEiOiJjbWdkc2tkcGYxbWJoMmpxdzV5dm10cjhhIn0.TpAnavdsPjHbTMD1N1OsEw';



        const cityInput = document.getElementById('city_g');

        const stateInput = document.getElementById('state_g');

        const countryInput = document.getElementById('country_g');

        const zipInput = document.getElementById('zipcode_g');



        // Initialize Geocoder

        const geocoder = new MapboxGeocoder({

            accessToken: mapboxgl.accessToken,

            types: 'place,locality',

            placeholder: 'Enter city name',

            minLength: 3,

            limit: 5,

        });



        // Append geocoder to the City input

        cityInput.parentNode.insertBefore(geocoder.onAdd(mapboxgl.Map({})), cityInput.nextSibling);



        // When a result is selected

        geocoder.on('result', function (e) {

            const context = e.result.context || [];

            console.log('Geocoder result context:', context);

            let state = '', country = '', postcode = '';



            context.forEach(function (c) {

                if (c.id.includes('region')) state = c.text;

                if (c.id.includes('country')) country = c.text;

                if (c.id.includes('postcode')) postcode = c.text;

            });



            // Sometimes postcode is part of the feature itself

            if (!postcode && e.result.place_type.includes('postcode')) {

                postcode = e.result.text;

            }



            cityInput.value = e.result.text || '';

            stateInput.value = state;

            countryInput.value = country;

            zipInput.value = postcode;

        });

    </script> -->





    <!-- <script src="{{ asset('backend/admin/assets/js/get-locations.js') }}"></script> -->

     <script>

        $(document).ready(function () {

        // const base_url = window.location.origin ;

        const base_url_old = window.location.origin;

        // const base_url = base_url_old + "/ikoro";

        const base_url = base_url_old;

        // console.log('base_url', base_url)

        var country_trigger_cnt = 1;

        var state_trigger_cnt = 1;

        var city_trigger_cnt = 1;

        var zip_trigger_cnt = 1;

        $(".select2").select2({

            theme: "classic",

        });



        if (typeof host !== "undefined") {

            const fields = [

            { id: "country_id", value: host.country_id, delay: 0 },

            { id: "state_id", value: host.state_id, delay: 1000 },

            { id: "city_id", value: host.city_id, delay: 2000 },

            { id: "zip_id", value: host.zip_id, delay: 3000 },

            ];



            fields.forEach((field) => {

            if (field.value) {

                setTimeout(function () {

                $("#" + field.id + ", ." + field.id)

                    .val(field.value)

                    .trigger("change");

                }, field.delay);

            }

            });

        }



        $("#country_id, .country_id").change(function () {

            var countryId = $(this).val();



            $("#state_id, .state_id")

            .empty()

            .append('<option value="">Select State</option>');

            $("#city_id, .city_id")

            .empty()

            .append('<option value="">Select City</option>');

            $("#zip_id, .zip_id")

            .empty()

            .append('<option value="">Select Zip</option>');

            console.log(base_url);



            if (countryId) {

            $.ajax({

                url: base_url + "/get-states/" + countryId,

                type: "GET",

                success: function (data) {

                $.each(data, function (key, value) {

                    $("#state_id, .state_id").append(

                    '<option value="' + value.id + '">' + value.name + "</option>"

                    );

                });

                },

            });

            }

        });



        $("#state_id, .state_id").change(function () {

            var stateId = $(this).val();

            $("#city_id, .city_id")

            .empty()

            .append('<option value="">Select City</option>');

            $("#zip_id, .zip_id")

            .empty()

            .append('<option value="">Select Zip</option>');



            if (stateId) {

            $.ajax({

                url: base_url + "/get-cities/" + stateId,

                type: "GET",

                success: function (data) {

                $.each(data, function (key, value) {

                    $("#city_id, .city_id").append(

                    '<option value="' + value.id + '">' + value.name + "</option>"

                    );

                });

                },

            });

            }

        });



        $("#city_id, .city_id").change(function () {

            var cityId = $(this).val();

            $("#zip_id, .zip_id")

            .empty()

            .append('<option value="">Select Zip</option>');



            if (cityId) {

            $.ajax({

                url: base_url + "/get-zips/" + cityId,

                type: "GET",

                success: function (data) {

                $.each(data, function (key, value) {

                    $("#zip_id, .zip_id").append(

                    '<option value="' + value.id + '">' + value.code + "</option>"

                    );

                });

                },

            });

            }



            updateMap();

        });

        function updateMap() {

            var cityId = document.getElementById("city_id").value;



            if (!cityId) return;



            var cityName = $("#city_id option:selected").text().trim();



            // console.log("Selected city:", cityName);



            var cityFormatted = encodeURIComponent(cityName); // Proper URL encoding



            var newSrc =

            "https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q=" +

            cityFormatted;



            document.getElementById("map").src = newSrc;

        }

        });



     </script>

    @include('host.layout.header_country_state')

@endsection

