<?= $this->element('navbar-team'); ?>
<div class="container-fluid mt-4">
    <div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
        <div class="form-group">
            <input type="text" class="form-control" id="pac-input" placeholder="Search for place">
        </div>
        <div style="width: 100%; height: 200px;" id="map"></div>
        <?= $this->Form->create($place) ?>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <?= $this->Form->control('name',['class' => 'form-control', 'required', 'readonly' => 'readonly']) ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <?= $this->Form->input('address',['class' => 'form-control', 'required', 'readonly' => 'readonly']) ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <?= $this->Form->input('latlng',['class' => 'form-control', 'required', 'readonly' => 'readonly']) ?>
                </div>
            </div>
        </div>
        <?= $this->Form->button(__('Add Place'),['class'=>'btn btn-success btn-lg float-right login_button mt-5']); ?>
        <?= $this->Form->end() ?>
    </div>
</div>
<script>

	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 54.5260, lng: 15.2551},
			zoom: 3,
			disableDefaultUI: true,
			mapTypeId: 'roadmap'
		});

		// Create the search box and link it to the UI element.
		var input = document.getElementById('pac-input');
		var searchBox = new google.maps.places.SearchBox(input);
		// map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

		// Bias the SearchBox results towards current map's viewport.
		map.addListener('bounds_changed', function() {
			searchBox.setBounds(map.getBounds());
		});

		var markers = [];
		// Listen for the event fired when the user selects a prediction and retrieve
		// more details for that place.
		searchBox.addListener('places_changed', function() {
			var places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}

			// Clear out the old markers.
			markers.forEach(function(marker) {
				marker.setMap(null);
			});
			markers = [];

			// For each place, get the icon, name and location.
			var bounds = new google.maps.LatLngBounds();
			places.forEach(function(place) {
				if (!place.geometry) {
					console.log("Returned place contains no geometry");
					return;
				}

				// Create a marker for each place.
				markers.push(new google.maps.Marker({
					map: map,
					title: place.name,
					position: place.geometry.location
				}));
				$('#name').val(place.name);
				$('#address').val(place.formatted_address);
				$('#latlng').val("lat/lng: " + place.geometry.location);

				console.log(place);

				if (place.geometry.viewport) {
					// Only geocodes have viewport.
					bounds.union(place.geometry.viewport);
				} else {
					bounds.extend(place.geometry.location);
				}
			});
			map.fitBounds(bounds);
		});
	}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDe_7atIUkDg9kljPsVDF6NnRrAzg2yOyo&libraries=places&callback=initMap"
        async defer></script>
