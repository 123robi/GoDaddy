<?= $this->element('navbar-team'); ?>

<div class="container-fluid mt-3">
    <?php if (!empty($event)): ?>
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-header">
                Next event
                <div class="pull-right">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">
                    <td><?= $this->Time->format($event->start,'d.MM.y HH:mm'); ?> -> <?= $this->Time->format($event->end,'HH:mm'); ?></td>
                </h5>
                <p class="card-text"><?= h($place->name) ?></p>
                <div id="map">
                </div>
                <p class="card-text"><?= h($place->description) ?></p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-header ">
                <div>
                    <span class="text-left">Event with this id does not exists!</span>
                    <?php echo $this->Html->link('Create new event',['controller' => 'Events', 'action' => 'add',
                    'team_id' => $team->id],['class' => 'text-right btn btn-success center-align']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
	function initMap() {
		var latlon = "<?php Print($place->latlng); ?>";

		var lat = latlon.substring(latlon.indexOf("(")+1, latlon.indexOf(","));
		var lon = latlon.substring(latlon.indexOf(",")+1, latlon.indexOf(")"));
		var location = {lat:  parseFloat(lat), lng:  parseFloat(lon)}
		var map = new google.maps.Map(document.getElementById("map"),{
			zoom: 13,
			center: location,
			disableDefaultUI: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		var marker = new google.maps.Marker({
			position: location,
			map: map
		});
	}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgSauPHDiY8Bz7uk0ryRqbnYxi4cjSEkQ&callback=initMap">

</script>

