<?= $this->element('navbar-team'); ?>
<div>
	<div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
		<div id='calendar'></div>
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="name"></h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<b><p id="time"></p></b>
						<b><p id="timeTo"></p></b>
						<p class="card-text" id="description"></p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-primary" id="eventView" href="">Details</a>
						<button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		$('#calendar').fullCalendar({
			header: {
				right: 'prev,next',
				left:'title',
			},
			timeFormat: 'H:mm',
			theme: true,
			themeSystem: 'bootstrap4',
			height: 'auto',
			selectHelper: true,
			eventSources: [

				{
					url: 'http://rkosir.eu/FeeCollector/eventsApi/getEventsEvent?id=76',
					color: '#8A66D9'
				},
				{
					url: 'http://rkosir.eu/FeeCollector/eventsApi/getEventsTraining?id=76',
					color: '#F38C70',
				},
				{
					url: 'http://rkosir.eu/FeeCollector/eventsApi/getEventsMatch?id=76',
					color: '#8ABF3E',
				}

			],

			eventColor: '#378006',
			eventRender: function(event, element) {
				element.qtip({
					content: event.id
				});
			},
			eventClick: function(calEvent, jsEvent, view) {
				const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
					"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
				];
				var date = new Date(calEvent.start);
				var day = date.getDate();
				var month = monthNames[date.getMonth()];
				var year = date.getFullYear();
				var hour  = "" + date.getHours()-1;
				var minutes = "" + date.getMinutes() ;
				if (minutes.length == 1) {
					minutes = "0" + minutes;
				}
				var output = day + " " + month + " " + year + " " + hour + ":" + minutes;
				console.log(hour.length);
				console.log(hour);

				var date = new Date(calEvent.end);
				var day = date.getDate();
				var month = monthNames[date.getMonth()];
				var year = date.getFullYear();
				var hour  = "" + date.getHours()-1;
				var minutes = "" + date.getMinutes() ;
				if (minutes.length == 1) {
					minutes = "0" + minutes;
				}
				var to = day + " " + month + " " + year + " " + hour + ":" + minutes;

				$('#modal').modal('show');
				$('#name').text(calEvent.name);
				$('#description').text(calEvent.description);
				$("#eventView").attr("href", "http://rkosir.eu/FeeCollector/teams/"+ calEvent.team_id +"/events/view/" + calEvent.id);
				$('#time').text("From: " + output);
				$('#timeTo').text("To: " + to);
				// window.location.assign("http://rkosir.eu/FeeCollector/teams/"+ calEvent.team_id +"/events/view/" + calEvent.id);
			},
		});
	});
</script>
