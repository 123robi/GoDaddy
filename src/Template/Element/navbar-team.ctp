<nav class="navbar navbar-expand-lg navbar-light">
	<a class="navbar-brand" href="<?php echo $this->Url->build(array('controller'=>'Teams','action'=>'index')) ?>">FeeCollector</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $this->Url->build(array('controller'=>'Teams','action'=>'view', $team->id)) ?>">Show team <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $this->Url->build(array('controller' => 'Events', 'action' => 'index','team_id' => $team->id)) ?>">Events</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $this->Url->build(array('controller' => 'Events', 'action' => 'add','team_id' => $team->id)) ?>">Add Event</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $this->Url->build(array('controller' => 'TeamMembers', 'action' => 'index','team_id' => $team->id)) ?>">Members</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $this->Url->build(array('controller' => 'Fees', 'action' => 'add','team_id' => $team->id)) ?>">Add Fee</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $this->Url->build(array('controller' => 'UsersFees', 'action' => 'add','team_id' => $team->id)) ?>">Add Fee to Member</a>
			</li>
		</ul>
	</div>
</nav>
