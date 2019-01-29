<?= $this->element('navbar-team'); ?>
<div class="container-fluid mt-4">
	<div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
		<?= $this->Form->create($usersFee) ?>
		<div class="form-group">
			<?= $this->Form->control('user_id',['options' => $users,'class' => 'form-control', 'required','empty' => 'Choose User']) ?>
		</div>
		<div class="form-group">
			<?= $this->Form->control('fee_id',['options' => $fees,'class' => 'form-control', 'required','empty' => 'Choose Fee']) ?>
		</div>
		<div class="form-group datepicker">
			<div class="input text required label">
				<?= $this->Form->label('Date'); ?>
			</div>
			<div class="input-group date" id="datetimepicker3" data-target-input="nearest">
				<?= $this->Form->control('date',['label'=> false,'class' => 'form-control datetimepicker-input', 'required','data-target' => '#datetimepicker3', 'type' => 'text','readonly','value' => '']) ?>
				<div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
				</div>
			</div>
		</div>
		<?= $this->Html->link(__('Add new Fee'), ['controller' => 'Fees', 'action' => 'add', 'team_id' => $team->id],['class' => 'btn btn-primary']) ?>
		<?= $this->Form->button(__('Add Fee to Member'),['class'=>'btn btn-success pull-right mb-3']); ?>
		<?= $this->Form->end() ?>
	</div>
	<table class="table table-striped col-lg-8 offset-lg-2">
		<thead>
		<tr>
			<th nowrap>Name</th>
			<th nowrap>Fee Name</th>
			<th nowrap class="d-none d-xl-table-cell">Paid</th>
			<th nowrap>Date</th>
			<th class="text-right"><em class="fa fa-cog"></em></th>
		</tr>
		</thead>
		<?php foreach ($userFees as $fee): ?>
		<tr>
			<td nowrap><?= h($fee->_matchingData['Users']->name) ?></td>
			<td nowrap><?= h($fee->_matchingData['Fees']->name) ?></td>
			<td nowrap class="d-none d-xl-table-cell"><?= h($fee->paid) ?></a></td>
			<td nowrap><?= h($fee->date) ?></td>
			<td class="text-right">
				<a class="btn btn-success" href="<?php echo $this->Url->build([
					'controller'=>'TeamMembers',
					'action'=>'view',
					'team_id' => $team->id,
					$fee->_matchingData['Users']->id
					]) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
