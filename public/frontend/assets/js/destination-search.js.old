// Airbnb-style Hierarchical Destination Search
(function() {
    const searchInput = document.getElementById('citySearchByInput');
    const dropdown = document.getElementById('cityDropdown');
    const selectedCityIdInput = document.getElementById('selectedCityId');
    const locationTypeInput = document.getElementById('locationType');
    const searchUrl = searchInput ? searchInput.dataset.url : '';

    if (!searchInput || !dropdown) {
        console.warn('[DestinationSearch] Search input or dropdown not found');
        return;
    }

    let debounceTimer;

    // Handle input changes with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
            dropdown.classList.remove('show');
            dropdown.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchLocations(query);
        }, 300);
    });

    // Fetch locations from API
    function fetchLocations(query) {
        fetch(`${searchUrl}?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => {
                console.error('[DestinationSearch] Error fetching locations:', error);
                dropdown.classList.remove('show');
            });
    }

    // Display search results with hierarchy and icons
    function displayResults(results) {
        if (!results || results.length === 0) {
            dropdown.innerHTML = '<div class="dropdown-item text-muted">No destinations found</div>';
            dropdown.classList.add('show');
            return;
        }

        dropdown.innerHTML = '';

        results.forEach(location => {
            const item = document.createElement('div');
            item.className = 'dropdown-item destination-result-item';
            item.style.cursor = 'pointer';
            item.style.display = 'flex';
            item.style.alignItems = 'center';
            item.style.padding = '12px 20px';
            item.style.transition = 'background-color 0.2s';

            // Add icon based on type
            const icon = document.createElement('i');
            icon.className = `fas ${location.icon || 'fa-map-marker-alt'}`;
            icon.style.marginRight = '12px';
            icon.style.color = getTypeColor(location.type);
            icon.style.fontSize = '16px';

            // Add location name with hierarchy
            const textContainer = document.createElement('div');
            textContainer.style.flex = '1';

            const mainText = document.createElement('div');
            mainText.textContent = location.name;
            mainText.style.fontWeight = '500';
            mainText.style.color = '#333';

            const typeLabel = document.createElement('small');
            typeLabel.textContent = location.type;
            typeLabel.style.color = '#999';
            typeLabel.style.fontSize = '12px';
            typeLabel.style.display = 'block';

            textContainer.appendChild(mainText);
            textContainer.appendChild(typeLabel);

            item.appendChild(icon);
            item.appendChild(textContainer);

            // Click handler
            item.addEventListener('click', function() {
                selectLocation(location);
            });

            // Hover effects
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });

            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'transparent';
            });

            dropdown.appendChild(item);
        });

        dropdown.classList.add('show');
    }

    // Select a location
    function selectLocation(location) {
        searchInput.value = location.name;
        selectedCityIdInput.value = location.id;
        locationTypeInput.value = location.type;
        dropdown.classList.remove('show');
        dropdown.innerHTML = '';

        console.log('[DestinationSearch] Selected:', location);
    }

    // Get color based on location type
    function getTypeColor(type) {
        const colors = {
            'Country': '#FF5A5F',
            'State': '#00A699',
            'City': '#FC642D',
            'Zipcode': '#484848'
        };
        return colors[type] || '#767676';
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    // Prevent form submission when selecting from dropdown
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && dropdown.classList.contains('show')) {
            e.preventDefault();
        }
    });

    console.log('[DestinationSearch] Initialized successfully');
})();
