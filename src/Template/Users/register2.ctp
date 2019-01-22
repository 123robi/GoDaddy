<div class="login">
	<img src="https://rkosir.eu/FeeCollector/img/logo.png" height="200px" width="200px" class="center">
	<div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
		<?= $this->Form->create($user) ?>
		<div class="form-group">
			<?= $this->Form->control('name',['class' => 'form-control','required']) ?>
		</div>
		<div class="form-group">
			<?= $this->Form->control('password',['class' => 'form-control', 'required','type' => 'password']) ?>
		</div>
		<div class="form-group">
			<?= $this->Form->control('phone_number',['class' => 'form-control', 'required','type' => 'tel']) ?>
		</div>
		<div class="form-group">
			<?= $this->Form->control('address',['class' => 'form-control', 'required']) ?>
		</div>
		<?= $this->Form->button(__('Register'),['class'=>'btn btn-success btn-lg float-right login_button mt-5']); ?>
		<?= $this->Form->end() ?>

		<div class="margin-top">
			<p class="center-align">Already have an account? <b><?php echo $this->Html->link('Sign in',['controller' => 'Users', 'action' => 'login'],['class' => 'center-align']); ?></b> </p>
		</div>
	</div>
</div>

