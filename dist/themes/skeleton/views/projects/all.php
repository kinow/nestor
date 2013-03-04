<div>
	<h1>Projects</h1>
</div>

<div class='row'>
	<div class='two columns'>
		<br/>
		<ul>
			<li>
				<?php echo anchor('/projects/create', 'New Project') ?>
			</li>
		</ul>
	</div>
	<div class='ten columns'>
		<div id="projects">
			<?php echo $pagination->create_links(); ?>
			<table>
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
			<?php echo $pagination->create_links(); ?>
		</div>
	</div>
</div>
