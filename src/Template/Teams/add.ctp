<?= $this->element('navbar'); ?>
<div>
	<div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
		<?= $this->Form->create($team) ?>
		<div class="form-group">
			<?= $this->Form->control('team_name',['class' => 'form-control','required']) ?>
		</div>
		<div class="form-group">
			<div class="input text required">
				<?= $this->Form->label('Currency Symbol'); ?>
			</div>
			<?= $this->Form->select('currency_symbol', $currencies, ['empty' => 'Choose one','class' => 'form-control','required']) ?>
		</div>
		<?= $this->Form->button(__('Create team'),['class'=>'btn btn-success btn-lg float-right login_button mt-5']); ?>
		<?= $this->Form->end() ?>
	</div>
</div>
