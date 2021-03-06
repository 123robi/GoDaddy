<?= $this->element('navbar-team'); ?>
<div class="container-fluid mt-4">
    <div class="col-md-12 col-sm-12 col-lg-6 offset-lg-3">
        <?= $this->Form->create($event) ?>
        <div class="form-group">
            <div class="input text required">
                <?= $this->Form->label('Name'); ?>
            </div>
            <?= $this->Form->select('name', ['Training' => 'Training','Match' => 'Match','Event' => 'Event'], ['empty' => 'Choose one','class' => 'form-control','required']) ?>
        </div>
        <div class="form-group datepicker">
            <div class="input text required label">
                <?= $this->Form->label('Start'); ?>
            </div>
            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                <?= $this->Form->control('start',['label'=> false,'class' => 'form-control datetimepicker-input', 'required','data-target' => '#datetimepicker1', 'type' => 'text','readonly','value' => '']) ?>
                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group datepicker">
            <div class="input text required label">
                <?= $this->Form->label('End'); ?>
            </div>
            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                <?= $this->Form->control('end',['label'=> false,'class' => 'form-control datetimepicker-input', 'required','data-target' => '#datetimepicker1', 'type' => 'text','readonly','value' => '']) ?>
                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= $this->Form->control('description',['class' => 'form-control', 'required']) ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('place_id',['options' => $places,'class' => 'form-control', 'required']) ?>
         </div>
        <?= $this->Html->link(__('Add new Place'), ['controller' => 'Places', 'action' => 'add', 'team_id' => $team->id],['class' => 'btn btn-primary']) ?>
        <?= $this->Form->button(__('Add Event'),['class'=>'btn btn-success float-right']); ?>
        <?= $this->Form->end() ?>
    </div>
</div>
