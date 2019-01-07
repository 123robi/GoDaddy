<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersFee $usersFee
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Users Fee'), ['action' => 'edit', $usersFee->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users Fee'), ['action' => 'delete', $usersFee->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersFee->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users Fees'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Fee'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Fees'), ['controller' => 'Fees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Fee'), ['controller' => 'Fees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="usersFees view large-9 medium-8 columns content">
    <h3><?= h($usersFee->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $usersFee->has('user') ? $this->Html->link($usersFee->user->name, ['controller' => 'Users', 'action' => 'view', $usersFee->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fee') ?></th>
            <td><?= $usersFee->has('fee') ? $this->Html->link($usersFee->fee->name, ['controller' => 'Fees', 'action' => 'view', $usersFee->fee->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Team') ?></th>
            <td><?= $usersFee->has('team') ? $this->Html->link($usersFee->team->id, ['controller' => 'Teams', 'action' => 'view', $usersFee->team->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($usersFee->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paid') ?></th>
            <td><?= $this->Number->format($usersFee->paid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($usersFee->date) ?></td>
        </tr>
    </table>
</div>
