<div class="login">
	<img src="https://rkosir.eu/FeeCollector/img/logo.png" height="200px" width="200px" class="center">
	<div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
		<?= $this->Form->create() ?>
		<div class="form-group">
			<?= $this->Form->control('email',['class' => 'form-control form-control-lg', 'required', 'type' => 'email', 'placeholder' => 'Email']) ?>
		</div>
		<div class="form-group">
			<?= $this->Form->control('password',['class' => 'form-control form-control-lg', 'required','type' => 'password',  'placeholder' => 'Password']) ?>
		</div>
		<?= $this->Form->button(__('Login'),['class'=>'btn btn-success btn-lg float-right login_button mt-5']); ?>
		<?= $this->Form->end() ?>
		<?echo $this->Form->postLink(
		'Login with Facebook',
		[
		'prefix' => false,
		'plugin' => 'ADmad/SocialAuth',
		'controller' => 'Auth',
		'action' => 'login',
		'provider' => 'facebook',
		'?' => ['redirect' => $this->request->getQuery('redirect')]
		],
		['class'=>'btn btn-success btn-lg float-right login_button mt-3 mb-3 facebook']
		);?>

		<div class="margin-top">
			<p class="center-align">Do not have an account? <b><?php echo $this->Html->link('Sign up',['controller' => 'Users', 'action' => 'register1'],['class' => 'center-align']); ?></b> </p>
		</div>
	</div>
</div>
