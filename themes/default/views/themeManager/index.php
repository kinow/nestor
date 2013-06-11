<div class='page-header'>
	<h1>Themes</h1>
</div>

<div class='row'>
	<div class='span12'>
		<div id="projects">
			<ul class="nav nav-tabs" id="myTab">
			  <li class="active"><a href="#updates">Updates</a></li>
			  <li><a href="#available">Available</a></li>
			  <li><a href="#installed">Installed</a></li>
			  <li><a href="#advanced">Advanced</a></li>
			</ul>
			<div class="tab-content">
			  <div class="tab-pane active" id="updates">
			  	<h2>Updates</h2>
			  </div>
			  <div class="tab-pane" id="available">
			  	<h2>Themes available</h2>
			  	<table class='table table-bordered table-hover'>
					<thead>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>URL</th>
							<th>Author</th>
							<th>Version</th>
							<th>Install</th>
						</tr>
					</thead>
					<?php if (isset($available)): ?>
					<tbody>
				  	<?php foreach($available as $theme): ?>
				  		<tr>
							<td><?php echo $theme->name; ?></td>
							<td><?php echo $theme->description; ?></td>
							<td><?php echo $theme->url; ?></td>
							<td><?php echo $theme->author; ?></td>
							<td><?php echo $theme->version; ?></td>
							<?php if ($theme->installed) :?>
							<td>Installed</td>
							<?php else: ?>
							<td><?php echo anchor('themeManager/install_theme/' . $theme->name, 'Install');?></td>
							<?php endif; ?>
						</tr>
				  	<?php endforeach; ?>
				  	</tbody>
				  	<?php endif; ?>
			  	</table>
			  </div>
			  <div class="tab-pane" id="installed">
			  	<h2>Themes installed</h2>
			  	<table class='table table-bordered table-hover'>
					<thead>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>URL</th>
							<th>Author</th>
							<th>Version</th>
							<th>Activate</th>
						</tr>
					</thead>
					<?php if (isset($themes)): ?>
					<tbody>
				  	<?php foreach($themes as $theme): ?>
				  		<tr>
							<td><?php echo $theme->name; ?></td>
							<td><?php echo $theme->description; ?></td>
							<td><?php echo $theme->url; ?></td>
							<td><?php echo $theme->author; ?></td>
							<td><?php echo $theme->version; ?></td>
							<td><?php echo anchor('themeManager/switch_theme/' . $theme->name, 'Activate');?></td>
						</tr>
				  	<?php endforeach; ?>
				  	</tbody>
				  	<?php endif; ?>
			  	</table>
			  </div>
			  <div class="tab-pane" id="advanced">
			  	<h2>Advanced</h2>
			  	<!-- who's copying? -->
			  	<p>TODO: add proxy form</p>
			  	<p>TODO: add upload form</p>
			  	<p>TODO: add update site form</p>
			  </div>
			</div>
		</div>
	</div>
</div>
