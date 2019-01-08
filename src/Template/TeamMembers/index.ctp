<?= $this->element('navbar-team'); ?>
<div class="mt-3">
	<table class="table table-striped">
		<thead>
		<tr>
			<th>#</th>
			<th nowrap>Member</th>
			<th nowrap class="d-none d-xl-table-cell">Email</th>
			<th nowrap class="d-none d-xl-table-cell">Phone number</th>
			<th nowrap class="d-none d-xl-table-cell">Address</th>
			<th nowrap>Fee to pay</th>
			<th class="text-right"><em class="fa fa-cog"></em></th>

		</tr>
		</thead>
		<?php foreach ($admin as $member): ?>
		<tr>
			<td nowrap><img src="http://rkosir.eu/images/<?= h($member->email) ?>.jpg" onerror="this.src='http://rkosir.eu/images/robikoser@gmail.com.jpg'", height="48" width="48"></td>
			<td nowrap><?= h($member->name) ?><br><small><i>admin</i></small></td>
			<td nowrap class="d-none d-xl-table-cell"><?= h($member->email) ?></td>
			<td nowrap class="d-none d-xl-table-cell"><a href="tel:<?= h($member->phone_number) ?>"><?= h($member->phone_number) ?></a></td>
			<td nowrap class="d-none d-xl-table-cell"><?= h($member->address) ?></td>
			<?php  $hasFee = 0;
				foreach ($fees as $fee):
				if($fee->user_id == $member->id) {
					$hasFee = 1; ?>
					<td nowrap><?= h($fee->sum) ?> <?= h($team->currency_symbol) ?></td>
				 <?php }
				?>
			<?php endforeach;
				 if($hasFee == 0) { ?>
				 <td nowrap>0 <?= h($team->currency_symbol) ?></td>
			<?php  }
			   ?>
			<td>
				<?= $this->Form->postButton(
				$this->Html->tag('i', '', array('class' => 'fa fa-trash')),
				['controller' => 'TeamMembers', 'action' => 'delete','team_id' => $team->id, $member->id],
				['confirm' => __('Are you sure you want to delete this Member?'),'class' => 'btn btn-danger pull-right']
				)
				?>
				<a class="btn btn-success pull-right" href="<?php echo $this->Url->build([
				'controller'=>'TeamMembers',
				'action'=>'view',
				'team_id' => $team->id,
				$member->id
				]) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>

			</td>
		</tr>
		<?php endforeach; ?>
		<?php foreach ($members as $member): ?>
		<tr>
			<td nowrap><img src="http://rkosir.eu/images/<?= h($member->email) ?>.jpg" onerror="this.src='http://rkosir.eu/images/robikoser@gmail.com.jpg'", height="48" width="48"></td>
			<td nowrap><?= h($member->name) ?><br><small><i>member</i></small></td>
			<td nowrap class="d-none d-xl-table-cell"><?= h($member->email) ?></td>
			<td nowrap class="d-none d-xl-table-cell"><a href="tel:<?= h($member->phone_number) ?>"><?= h($member->phone_number) ?></a></td>
			<td nowrap class="d-none d-xl-table-cell"><?= h($member->address) ?></td>
			<?php $hasFee = 0;
				foreach ($fees as $fee):
				if($fee->user_id == $member->id) {
				$hasFee = 1; ?>
				<td nowrap><?= h($fee->sum) ?> <?= h($team->currency_symbol) ?></td>
			<?php }
			   ?>
			<?php endforeach;  if($hasFee == 0) { ?>
			<td nowrap>0 <?= h($team->currency_symbol) ?></td>
			<?php  }
			   ?>
			<td>
				<?= $this->Form->postButton(
				$this->Html->tag('i', '', array('class' => 'fa fa-trash')),
				['controller' => 'TeamMembers', 'action' => 'delete','team_id' => $team->id, $member->id],
				['confirm' => __('Are you sure you want to delete this Member?'),'class' => 'btn btn-danger pull-right']
				)
				?>
				<a class="btn btn-success pull-right" href="<?php echo $this->Url->build([
				'controller'=>'TeamMembers',
				'action'=>'view',
				'team_id' => $team->id,
				$member->id
				]) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>


