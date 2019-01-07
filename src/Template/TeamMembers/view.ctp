<?= $this->element('navbar-team'); ?>

<div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

			</div>
		</div>
	</div>
</div>
<div class="container-fluid mt-3">
	<div class="row">
		<div class="col-lg-4">
				<div class="card">
					<img src="http://rkosir.eu/images/<?= h($member->email) ?>.jpg" onerror="this.src='http://rkosir.eu/images/robikoser@gmail.com.jpg'", height="auto" width="100%">
					<div class="card-body">
						<h5 class="card-title card_user_name"><?= h($member->name) ?></h5>
						<b>Phone number: </b><a href="tel:<?= h($member->phone_number) ?>" class="pull-right"><?= h($member->phone_number) ?></a><br>
						<b>Address: </b><a href="https://maps.google.com/?q=<?= h($member->address) ?>" class="pull-right"><?= h($member->address) ?></a>
					</div>
				</div>
		</div>
		<div class="col-lg-8">
		<?php foreach ($fees as $fee): ?>
			<div class="row">
				<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-5">
										<small><?= $this->Time->format($fee->date,'d.MM.y HH:mm'); ?></small>
										<br>
										<?= h($fee->_matchingData['Fees']->name) ?>
									</div>
									<div class="col-4">
										<div class="text-right">
											<?php if ($fee->paid == 1): ?>
											<small class="paid">PAID</small>
											<?php else: ?>
											<small class="not_paid">NOT PAID</small>
											<?php endif; ?>
											<br>
											<span class=""><?= h($fee->_matchingData['Fees']->cost) ?> <?= h($team->currency_symbol) ?></span>
										</div>
									</div>
									<?php if ($fee->paid == 0): ?>
										<div class="col-3 my-auto">
											<div class="pull-right">
												<?= $this->Form->postButton(
												__('Pay'),
												['controller' => 'UsersFees', 'action' => 'change','team_id' => $team->id,'user_id' => $member->id, $fee->id],
												['confirm' => __('Are you sure you want continue and pay the fee? \nFEE: {0}\nAT: {1}', $fee->_matchingData['Fees']->name,$this->Time->format($fee->date,'d.MM.y HH:mm')),'class' => 'btn btn-success']
												)
											?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>
