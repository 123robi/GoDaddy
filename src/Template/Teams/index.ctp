<?= $this->element('navbar'); ?>
<div class="mt-3">
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="d-none d-xl-table-cell">#</th>
            <th nowrap>Team name</th>
            <th class="d-none d-xl-table-cell">Currency code</th>
            <th nowrap>Currency symbol</th>
            <th>Admin</th>
            <th class="d-none d-xl-table-cell">Created</th>
            <th class="d-none d-xl-table-cell">Modified</th>
            <th class="text-right"><em class="fa fa-cog"></em></th>

        </tr>
        </thead>
        <?php foreach ($adminTeams as $team): ?>
        <tr>
            <td nowrap class="d-none d-xl-table-cell"><?= $this->Number->format($team->id) ?></td>
            <td><?= h($team->team_name) ?></td>
            <td class="d-none d-xl-table-cell"><?= h($team->currency_code) ?></td>
            <td><?= h($team->currency_symbol) ?></td>
            <td>Yes</td>
            <td class="d-none d-xl-table-cell"><?= h($team->created) ?></td>
            <td class="d-none d-xl-table-cell"><?= h($team->modified) ?></td>
            <td>
                <?= $this->Form->postButton(
                $this->Html->tag('i', '', array('class' => 'fa fa-trash')),
                ['controller' => 'Teams', 'action' => 'delete',$team->id],
                ['confirm' => __('Are you sure you want to delete this Team?'),'class' => 'btn btn-danger pull-right']
                )
                ?>
                <a class="btn btn-success pull-right" href="<?php echo $this->Url->build(['controller'=>'Teams','action'=>'view', $team->id]) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php foreach ($teams as $team): ?>
        <tr>
            <td class="d-none d-xl-table-cell"><?= $this->Number->format($team->id) ?></td>
            <td><?= h($team->team_name) ?></td>
            <td class="d-none d-xl-table-cell"><?= h($team->currency_code) ?></td>
            <td><?= h($team->currency_symbol) ?></td>
            <td>No</td>
            <td class="d-none d-xl-table-cell"><?= h($team->created) ?></td>
            <td class="d-none d-xl-table-cell"><?= h($team->modified) ?></td>
            <td class="text-center">
                <a class="btn btn-success pull-right" href="<?php echo $this->Url->build(['controller'=>'Teams','action'=>'view', $team->id]) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

