<div class='page-header'>
	<h1>Projects</h1>
</div>

<div class='row'>
	<div class='span2'>
		<ul class="nav nav-tabs nav-stacked">
			<li>
				<?php echo anchor('/projects/create', 'New Project') ?>
			</li>
		</ul>
	</div>
	<div class='span10'>
		<div id="projects">
			<?php echo $this->pagination->create_links(); ?>
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Description</th>
					</tr>
				</thead>
				<?php if (isset($projects)): ?>
				<tbody>
				<?php foreach ($projects as $project): ?>
					<tr>
						<td><?php echo $project->id ?></td>
						<td><?php echo $project->name ?></td>
						<td><?php echo $project->description ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
				<?php endif; ?>
			</table>
			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>
</div>
