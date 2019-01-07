
<div class="form-group">
    <input type="text" class="form-control" id="pac-input" placeholder="Search for place">
</div>
<?= $this->Form->create($place) ?>
<?= $this->Form->input('name',['class' => 'form-control', 'required', 'readonly' => 'readonly']) ?>
<?= $this->Form->input('address',['class' => 'form-control', 'required', 'readonly' => 'readonly']) ?>
<?= $this->Form->input('latlng',['class' => 'form-control', 'required', 'readonly' => 'readonly']) ?>
<?= $this->Form->button(__('Add Place'),['class'=>'btn btn-success btn-lg float-right login_button mt-5']); ?>
<?= $this->Form->end() ?>

<div style="width: 400px; height: 600px;" id="map"></div>
<script>

	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -33.8688, lng: 151.2195},
			zoom: 13,
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
