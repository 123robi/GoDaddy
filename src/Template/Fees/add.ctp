<?= $this->element('navbar-team'); ?>
<div class="container-fluid mt-4">
	<div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
		<?= $this->Form->create($fee) ?>
		<div class="form-group">
			<?= $this->Form->control('name',['class' => 'form-control', 'required']) ?>
		</div>
		<div class="form-group">
			<div class="input text required">
				<?= $this->Form->label('cost in '.$team->currency_symbol); ?>
			</div>
			<?= $this->Form->input('cost',['class' => 'form-control', 'required','type' => 'number','label' => false]) ?>
		</div>
		<?= $this->Html->link(__('Back'), ['controller' => 'UsersFees', 'action' => 'add', 'team_id' => $team->id],['class' => 'btn btn-primary']) ?>
		<?= $this->Form->button(__('Add Fee'),['class'=>'btn btn-success mb-3 pull-right']); ?>
		<?= $this->Form->end() ?>
	</div>

	<table class="table table-striped col-lg-8 offset-lg-2 mt-3">
		<thead>
		<tr>
			<th nowrap>Name</th>
			<th nowrap>Cost</th>
			<th class="text-right"><em class="fa fa-cog"></em></th>
		</tr>
		</thead>
		<?php foreach ($fees as $fee): ?>
		<tr>
			<td nowrap><?= h($fee->name) ?></td>
			<td nowrap><?= h($fee->cost) ?></td>
			<td class="text-right">
				<?= $this->Form->postButton(
				$this->Html->tag('i', '', array('class' => 'fa fa-trash')),
				['controller' => 'Fees', 'action' => 'delete','team_id' => $team->id,$fee->id],
				['confirm' => __('Are you sure you want to delete this Fee?'),'class' => 'btn btn-danger']
				)
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
