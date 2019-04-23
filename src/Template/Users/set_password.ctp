<div class="container-fluid mt-4">
	<div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
		<?= $this->Form->create($user) ?>
		<div class="form-group">
			<?= $this->Form->control('password',['class' => 'form-control', 'required', 'type' => 'password']) ?>
		</div>
		<div class="form-group">
			<?= $this->Form->control('phone_number',['class' => 'form-control', 'required']) ?>
		</div>
		<div class="form-group">
			<?= $this->Form->control('address',['class' => 'form-control', 'required']) ?>
		</div>
		<?= $this->Form->button(__('Continue to your teams'),['class'=>'btn btn-success pull-right mb-3']); ?>
		<?= $this->Form->end() ?>
	</div>
</div>
