$(document).ready(function() {
	$('#datetimepicker1').datetimepicker({
		ignoreReadonly: true,
		format: 'YYYY-MM-DD HH:mm'
	});
	$('#datetimepicker2').datetimepicker({
		ignoreReadonly: true,
		format: 'YYYY-MM-DD HH:mm'
	});

	$('#datetimepicker3').datetimepicker({
		ignoreReadonly: true,
		format: 'YYYY-MM-DD'
	});

	$('#confirmation').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var recipient = button.data('fee_id') // Extract info from data-* attributes
		// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		var modal = $(this)
		modal.find('.modal-title').text('New message to ' + recipient)
	})
});
