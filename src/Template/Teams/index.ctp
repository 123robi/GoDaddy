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
            <th class="text-center"><em class="fa fa-cog"></em></th>

        </tr>
        </thead>
        <?php foreach ($adminTeams as $team): ?>
        <tr>
            <td nowrap class="d-none d-xl-table-cell"><?= $this->Number->format($team->id) ?></td>
            <td nowrap><?= h($team->team_name) ?></td>
            <td class="d-none d-xl-table-cell"><?= h($team->currency_code) ?></td>
            <td><?= h($team->currency_symbol) ?></td>
            <td>Yes</td>
            <td class="d-none d-xl-table-cell"><?= h($team->created) ?></td>
            <td class="d-none d-xl-table-cell"><?= h($team->modified) ?></td>
            <td class="text-center">
                <a class="btn btn-success" href="<?php echo $this->Url->build(['controller'=>'Teams','action'=>'view', $team->id]) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
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
                <a class="btn btn-success" href="<?php echo $this->Url->build(['controller'=>'Teams','action'=>'view', $team->id]) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

