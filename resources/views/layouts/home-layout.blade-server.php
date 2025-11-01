<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/select-a-task.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/airbnb-search.css') }}" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/fontawesome.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
        <link rel="stylesheet" href="{{ asset('frontend/assets/owlcarousel/owl.carousel.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/assets/owlcarousel/owl.theme.default.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom-owl.css?v=0.0001') }}" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap"
            rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Allura&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"
            rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css"
            rel="stylesheet" />
        <link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mapbox/search-js-web@1.0.0-beta.21/dist/style.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@mapbox/search-js-web@1.0.0-beta.21/dist/web.js"></script>
        {{-- Old search disabled in favor of Mapbox: <script src="{{ asset('frontend/assets/js/destination-search.js') }}" defer></script> --}}

        <style>
            .select-host-click {
                cursor: pointer;
            }


            .custom-card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }

            .card-content {
                display: flex;
                align-items: center;
            }

            .card-content img {
                width: 80px;
                margin-right: 20px;
            }

            .input-group-text {
                background: transparent;
                border: 0px;
                margin-top: -10px;
            }

            .city-icon {
                font-size: 14px;
            }

            .mars-icon {
                font-size: 13px;
            }

            .envelope-icon {
                font-size: 18px;
            }

            /* Destination Map Styles */
            .destination-map-container {
                background: #f7f7f7;
                padding: 30px 0;
            }

            .map-legend {
                display: flex;
                justify-content: center;
                margin-top: 15px;
                gap: 30px;
            }

            .legend-item {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #222;
                font-size: 14px;
            }

            .legend-icon {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: inline-block;
            }

            .mapboxgl-popup-content {
                padding: 15px;
                border-radius: 8px;
            }

            .mapboxgl-popup-content h4 {
                margin: 0 0 5px 0;
                font-size: 16px;
                font-weight: bold;
            }

            .mapboxgl-popup-content p {
                margin: 0;
                font-size: 14px;
            }
        </style>
    @endpush

    <!-- Modal -->

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog col-md-4">
            <div class="modal-content" style="background: rgb(0, 37, 2);">
                <div class="modal-header">
                    <h5 class="host-modal-title nav-link" id="exampleModalLabel">Login Please</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <input type="hidden" id="loggedIn" value="{{ $loggedIn }}" />
                    <div class="modal-body">
                        <div class="form-group">
                            <x-input-label class="text-white" for="username" :value="__('Email')" />
                            <x-text-input type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label class="text-white" for="password" :value="__('Password')" />
                            <x-text-input id="password" type="password" name="password" required
                                autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="login-a">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                        <br />
                        <p class="nav-link">Don't have an account?</p>
                        <a href="{{ route('user.register') }}" class="login-a">SignUp</a>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="attend-event-add">
        <div class="container">
            <div class="row attend-event-inner">
                <div class="col-md-7 attend-event-left">
                    <h3>Tour the world from the comfort of
                        your home or Office with iKORO.</h3>
                </div>

                <div class="col-md-5 attend-event-right">
                    <img src="./frontend/images/teacher-three.png">
                </div>


            </div>
        </div>
    </div>

    <div class="content flex-grow-1 comfort-your-home">
        <div class="container-fluid bg-3 text-center">
            <!--     <span class="text-white order-text"><b>Order, relax, and tour places with iKORO from the comfort of your home /
                    office.</b></span>
 -->

            {{-- <div class="content flex-grow-1 mb-5 mt-3 meet-top-section-owl">
                <div class="container bg-3 text-center">
                    <div class="owl-carousel menu" id="owl-carousel-top"
                        style="display: flex; justify-content: center;">
                        @foreach ($tasks as $task)
                            <form class="host-new-filter" action="{{ route('filter.host') }}" method="GET">
                                @csrf
                                <button class="filter-submit" type="submit">
                                    <div class="owl-css" id="task" data-id="{{ $task->id }}"
                                        data-url="{{ route('filter.gigs') }}" style="cursor: pointer;">
                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                        <i class="{{ $task->icon }}"></i>
                                        {{ $task->title }}
                                    </div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div> --}}


            <div class="meet-top-section-owl">
                <div class="container">
                    <div class="row meet-top-section-inner">
                        @foreach ($tasks as $task)
                            <div class="col-sm-2">
                                <div class="card">
                                    <form class="host-new-filter" action="{{ route('filter.host') }}" method="GET">
                                        @csrf
                                        <button class="filter-submit" type="submit">
                                            <div class="card-block" id="task" data-id="{{ $task->id }}"
                                                data-url="{{ route('filter.gigs') }}" style="cursor: pointer;">
                                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                <i class="{{ $task->icon }}"></i>
                                                <p class="card-title">{{ $task->title }}</p>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="col-sm-2">
                            <div class="card">
                                <div class="card-block">
                                    <i class="fa-solid fa-city"></i>
                                    <p class="card-title">City Tours</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="card">
                                <div class="card-block">
                                    <i class="fa-solid fa-handshake"></i>
                                    <p class="card-title">Meeting/Event</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="card">
                                <div class="card-block">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                    <p class="card-title">Verify Places</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="card">
                                <div class="card-block">
                                    <i class="fa-solid fa-face-surprise"></i>
                                    <p class="card-title">Surprise Someone</p>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>



            <form id="filter-form" action="{{ route('filter.host') }}" method="GET">
                @csrf
                <div class="container search-destinations">
                    <!-- Destination Section -->
                    <!-- @php
                        $locationName = '';

                        if (old('location_id') || request('location_id')) {
                            $id = old('location_id', request('location_id'));
                            $type = old('location_type', request('location_type'));

                            if ($type === 'City') {
                                $location = \App\Models\City::find($id);
                            } elseif ($type === 'State') {
                                $location = \App\Models\State::find($id);
                            } elseif ($type === 'Country') {
                                $location = \App\Models\Country::find($id);
                            }

                            if (isset($location)) {
                                $locationName = $location->name . '-' . $type;
                            }
                        }
                    @endphp -->

                    <div class="mobile-add-destination">
                    <div class="destination-section">
                        <input type="text" name="location_name" id="citySearchByInput" class="search-destination"
                            placeholder="Search destinations"
                            autocomplete="address-line1" value="" />

                        <input type="hidden" name="location_id" id="selectedCityId"
                            value="{{ old('location_id', request('location_id')) }}">
                        <input type="hidden" name="location_type" id="locationType"
                            value="{{ old('location_type', request('location_type')) }}">
                        <input type="hidden" name="destination_latitude" id="destination_latitude">
                        <input type="hidden" name="destination_longitude" id="destination_longitude">
                        <input type="hidden" name="destination_city" id="destination_city">
                        <input type="hidden" name="destination_state" id="destination_state">
                        <input type="hidden" name="destination_country" id="destination_country">
                        <input type="hidden" name="destination_postcode" id="destination_postcode">

                     <i class="fa fa-location-arrow container location-add" aria-hidden="true"></i>


                    </div>

                    <!-- Service Section -->
                    <div class="service-section">
                        <select id="choose-service" name="task_id" class="choose-service">
                            <option value="" {{ old('task_id', request('task_id')) == '' ? 'selected' : '' }}>
                                Choose a service
                            </option>
                            @foreach ($tasks as $task)
                                <option value="{{ $task['id'] }}"
                                    {{ old('task_id', request('task_id')) == $task['id'] ? 'selected' : '' }}>
                                    {{ $task['title'] }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Tool Section -->
                     <div class="tool-services-add">
                    <div class="tool-section">
                        {{-- <select name="equipment_id" id="equipment_id" class="choose-tool">
                            <option value=""
                                {{ old('equipment_id', request('equipment_id')) == '' ? 'selected' : '' }}>Choose a
                                tool</option>
                            @foreach ($equipment_price_all as $row)
                                <option value="{{ $row->id }}"
                                    {{ old('equipment_id', request('equipment_id')) == $row->id ? 'selected' : '' }}>
                                    {{ $row->name }}
                                </option>
                            @endforeach
                        </select> --}}

                        <select id="is_open" name="is_open" class="choose-tool">
                            <option value="" {{ old('is_open', request('is_open')) == '' ? 'selected' : '' }}>
                                Every Host
                            </option>
                            <option value="1" {{ old('is_open', request('is_open')) == '1' ? 'selected' : '' }}>
                                Host Online
                            </option>
                        </select>
                    </div>

                    <!-- Gender Section -->
                    <div class="gender-section">
                        <select id="gender" name="gender" class="choose-gender">
                            <option value="" {{ old('gender', request('gender')) == '' ? 'selected' : '' }}>
                                Any Host
                            </option>
                            <option value="male" {{ old('gender', request('gender')) == 'male' ? 'selected' : '' }}>
                                Male
                            </option>
                            <option value="female"
                                {{ old('gender', request('gender')) == 'female' ? 'selected' : '' }}>
                                Female
                            </option>
                        </select>
                    </div>
                    </div>


                  </div>
                    <!-- Search Button -->
                    <div class="search-button">
                        <button type="submit" aria-label="Search">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!--      @if (Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif -->

            <!-- Destination Map Display -->
            <!-- <div id="destination-map-section" class="destination-map-container" style="margin-top: 30px; margin-bottom: 30px;">
                <div class="container">
                    <h3 class="text-center mb-3" style="color: #222; font-weight: 700;">Popular Destinations</h3>
                    <div id="destination-map" style="height: 400px; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div> -->

            <div id="destination-map-section" class="destination-map-container" style="margin-top: 40px; margin-bottom: 40px;">
                <div class="container">
                    <h3 class="text-center mb-4" style="color: #222; font-weight: 700; font-size: 28px;">Destination Map Display</h3>
                    <div id="destination-map" style="height: 450px; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>

                    <!-- <div id="map-legend" class="map-legend">
                        <div class="legend-item">
                            <span class="legend-icon" style="background-color: #FF5A5F;"></span>
                            <span class="legend-text" style="color: #222;">Most Searched</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-icon" style="background-color: #00A699;"></span>
                            <span class="legend-text" style="color: #222;">Popular</span>
                        </div>
                    </div> -->
                </div>
            </div>

            <div class="container">
                <!--    <div class="content flex-grow-1 mb-5 mt-3 meet-top-section-owl">
                    <div class="container-fluid bg-3 text-center">
                        <div class="owl-carousel menu" id="owl-carousel-top"
                            style="display: flex; justify-content: center;">
                            @foreach ($tasks as $task)
<form class="host-new-filter" action="{{ route('filter.host') }}" method="GET">
                                    @csrf
                                    <button class="filter-submit" type="submit">
                                        <div class="owl-css" id="task" data-id="{{ $task->id }}"
                                            data-url="{{ route('filter.gigs') }}" style="cursor: pointer;">
                                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                                            <i class="{{ $task->icon }}"></i><br />
                                            {{ $task->title }}
                                        </div>
                                    </button>
                                </form>
@endforeach
                        </div>
                    </div>
                </div> -->
                <!-- Main Page content -->
                @yield('page_conent')
                <!-- End Main Page content -->
            </div>



        </div>
    </div>

    @push('scripts')
        <script>
            function toggleDescription(gigId) {
                var shortDesc = document.getElementById("short-desc-" + gigId);
                var fullDesc = document.getElementById("full-desc-" + gigId);
                var loadMoreBtn = document.getElementById("load-more-btn-" + gigId);
                if (fullDesc.style.display === "none") {
                    fullDesc.style.display = "inline";
                    loadMoreBtn.innerText = "Show Less";
                } else {
                    fullDesc.style.display = "none";
                    loadMoreBtn.innerText = "Load More";
                }
            }
        </script>

        <script>
            let host = @json($where ?? []);
        </script>
        <!-- <script>
            const script = document.getElementById('search-js');
            script.onload = function() {
                mapboxsearch.autofill({
                    accessToken: 'k.eyJ1IjoiaWtvcm9ocSIsImEiOiJjbWdkc2tkcGYxbWJoMmpxdzV5dm10cjhhIn0.TpAnavdsPjHbTMD1N1OsEw'
                });
            };
        </script> -->

        <script
            defer
            src="https://api.mapbox.com/search-js/v1.2.0/web.js">
        </script>
        <!-- Mapbox Search Autofill for Destination Input -->
        <script>
            const MAPBOX_TOKEN = 'pk.eyJ1IjoiaWtvcm9ocSIsImEiOiJjbWdkc2tkcGYxbWJoMmpxdzV5dm10cjhhIn0.TpAnavdsPjHbTMD1N1OsEw';

            // Initialize Mapbox Address Autofill for search input
            window.addEventListener('load', () => {
                const searchInput = document.getElementById('citySearchByInput');

                if (!searchInput) {
                    console.warn('[Search] Input element not found');
                    return;
                }

                if (typeof mapboxsearch === 'undefined') {
                    console.warn('[Search] Mapbox Search library not loaded');
                    return;
                }

                try {
                    // Initialize with proper configuration
                    mapboxsearch.config.accessToken = MAPBOX_TOKEN;

                    const searchAutofill = mapboxsearch.autofill({
                        options: {
                            language: 'en',
                            // country: 'ng', // Nigeria (lowercase)
                            types: [
                                'country',
                                'region',
                                'postcode',
                                'district',
                                'place',              // Cities
                                'locality',
                                'neighborhood',
                                'address',
                                'poi'                 // Points of interest
                            ],
                            render: (feature) => {
                                const props = feature.properties || {};
                                const country = props.country || '';
                                const region = props.region || '';
                                const place = props.place || '';
                                const postcode = props.postcode || '';

                                // Airbnb-style display order
                                const displayText = [country, region, place, postcode].filter(Boolean).join(', ');

                                // Highlight main name (like Airbnb bolds the top name)
                                return `
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span style="font-weight:600; color:#222;">${place || region || country}</span>
                                        <span style="color:#666; font-size:13px;">${displayText}</span>
                                    </div>
                                `;
                            }
                        }
                    });

                    console.log('[Search] Mapbox autofill initialized');

                    // Listen for address selection
                    searchInput.addEventListener('retrieve', (e) => {
                        console.log('[Search] Address selected:', e);

                        if (!e.detail || !e.detail.features || !e.detail.features.length) {
                            console.warn('[Search] No features in event');
                            return;
                        }

                        const feature = e.detail.features[0];
                        console.log('[Search] Feature:', feature);

                        if (feature && feature.geometry && feature.geometry.coordinates) {
                            // Get coordinates
                            const [lng, lat] = feature.geometry.coordinates;
                            document.getElementById('destination_latitude').value = lat;
                            document.getElementById('destination_longitude').value = lng;

                            // Extract address components
                            let city = '', state = '', country = '', postcode = '';

                            if (feature.properties) {
                                // Try to get from properties
                                if (feature.properties.context) {
                                    Object.values(feature.properties.context).forEach(item => {
                                        if (item.id) {
                                            if (item.id.startsWith('place')) city = item.text || item.name;
                                            if (item.id.startsWith('region')) state = item.text || item.name;
                                            if (item.id.startsWith('country')) country = item.text || item.name;
                                            if (item.id.startsWith('postcode')) postcode = item.text || item.name;
                                        }
                                    });
                                }

                                // Fallback to direct properties
                                city = city || feature.properties.place || '';
                                state = state || feature.properties.region || '';
                                country = country || feature.properties.country || '';
                                postcode = postcode || feature.properties.postcode || '';
                            }

                            document.getElementById('destination_city').value = city;
                            document.getElementById('destination_state').value = state;
                            document.getElementById('destination_country').value = country;
                            document.getElementById('destination_postcode').value = postcode;

                            // Format display value
                            const fullLocation = [country, state, city, postcode].filter(Boolean).join(', ');

                            if (fullLocation) {
                                searchInput.value = fullLocation;
                            }

                            console.log('[Search] Location captured:', {
                                lat, lng, city, state, country, postcode
                            });
                        }
                    });
                } catch (error) {
                    console.error('[Search] Error initializing Mapbox autofill:', error);
                }
            });
        </script>

        <!-- Mapbox Destination Map Initialization -->
        <script>
            const topDestinations = @json($topDestinations ?? []);

            // Initialize map on page load
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof mapboxgl !== 'undefined' && document.getElementById('destination-map')) {
                    mapboxgl.accessToken = MAPBOX_TOKEN;

                    const map = new mapboxgl.Map({
                        container: 'destination-map',
                        style: 'mapbox://styles/mapbox/streets-v12', // Light, readable map
                        center: [8.6753, 9.0820], // Nigeria center (longitude, latitude)
                        zoom: 5.5,
                        projection: 'globe'
                    });

                    map.on('load', function() {
                        // Add markers for popular destinations
                        topDestinations.forEach((dest, index) => {
                            // Determine marker color based on popularity
                            const color = index === 0 ? '#FF5A5F' : '#00A699';

                            // // Get coordinates (you may need to add geocoding)
                            // let coords = [0, 0]; // Default
                            // Default coordinates for Nigeria (center)
                            let coords = [9.789039272567765, 8.097571834543599];

                            // Try to get coordinates from gigs if available
                            if (dest.latitude && dest.longitude) {
                                coords = [parseFloat(dest.longitude), parseFloat(dest.latitude)];
                            }

                            // Create marker
                            const el = document.createElement('div');
                            el.className = 'destination-marker';
                            el.style.backgroundColor = color;
                            el.style.width = '30px';
                            el.style.height = '30px';
                            el.style.borderRadius = '50%';
                            el.style.border = '3px solid white';
                            el.style.cursor = 'pointer';
                            el.style.boxShadow = '0 2px 10px rgba(0,0,0,0.3)';

                            // Create popup
                            const popup = new mapboxgl.Popup({ offset: 25 })
                                .setHTML(`
                                    <h4>${dest.full_location_name}</h4>
                                    <p><strong>Searches:</strong> ${dest.search_count}</p>
                                    <p><small>Last searched: ${new Date(dest.last_searched_at).toLocaleDateString()}</small></p>
                                `);

                            // Add marker to map
                            new mapboxgl.Marker(el)
                                .setLngLat(coords)
                                .setPopup(popup)
                                .addTo(map);
                        });

                        // Fit map to show all markers if we have destinations
                        if (topDestinations.length > 0 && topDestinations[0].latitude) {
                            const bounds = new mapboxgl.LngLatBounds();
                            topDestinations.forEach(dest => {
                                if (dest.latitude && dest.longitude) {
                                    bounds.extend([parseFloat(dest.longitude), parseFloat(dest.latitude)]);
                                }
                            });
                            map.fitBounds(bounds, { padding: 50, maxZoom: 10 });
                        }
                    });

                    // Add navigation controls
                    map.addControl(new mapboxgl.NavigationControl());
                }
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#filter-form1').on('submit', function(e) {
                    e.preventDefault(); // Prevent page reload

                    $.ajax({
                        url: "{{ route('filter.gigs') }}",
                        type: "GET",
                        data: $(this).serialize(), // Send form data
                        success: function(response) {
                            $('#gigs-container').html(response.html);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-guest-layout>
