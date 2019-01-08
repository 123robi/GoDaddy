<?= $this->element('navbar-team'); ?>
<div class="container-fluid mt-4">
    <div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
        <?= $this->Form->create($user) ?>
        <div class="form-group">
            <?= $this->Form->control('name',['class' => 'form-control', 'required']) ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('email',['class' => 'form-control', 'required', 'type' => 'email']) ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('admin',['class' => 'form-control','type' => 'checkbox']) ?>
        </div>
        <?= $this->Form->button(__('Add Member'),['class'=>'btn btn-success pull-right mb-3']); ?>
        <?= $this->Form->end() ?>
    </div>
</div>
