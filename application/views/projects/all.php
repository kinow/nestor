<h1>Projects</h1>

<div id="projects">
<?php if (isset($projects)): ?>
<?php foreach ($projects as $project): ?>
	<div class="project">
		<p><?php echo $project->id ?></p>
		<p><?php echo $project->name ?></p>
	</div>
<?php endforeach; ?>
<?php endif; ?>
</div>