<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersFee[]|\Cake\Collection\CollectionInterface $usersFees
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Users Fee'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Fees'), ['controller' => 'Fees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Fee'), ['controller' => 'Fees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="usersFees index large-9 medium-8 columns content">
    <h3><?= __('Users Fees') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fee_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('team_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usersFees as $usersFee): ?>
            <tr>
                <td><?= $this->Number->format($usersFee->id) ?></td>
                <td><?= $usersFee->has('user') ? $this->Html->link($usersFee->user->name, ['controller' => 'Users', 'action' => 'view', $usersFee->user->id]) : '' ?></td>
                <td><?= $usersFee->has('fee') ? $this->Html->link($usersFee->fee->name, ['controller' => 'Fees', 'action' => 'view', $usersFee->fee->id]) : '' ?></td>
                <td><?= $usersFee->has('team') ? $this->Html->link($usersFee->team->id, ['controller' => 'Teams', 'action' => 'view', $usersFee->team->id]) : '' ?></td>
                <td><?= $this->Number->format($usersFee->paid) ?></td>
                <td><?= h($usersFee->date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $usersFee->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersFee->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersFee->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersFee->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
