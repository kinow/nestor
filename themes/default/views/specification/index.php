<div class='page-header'>
	<h1>Test Specification</h1>
</div>
<div class='row'>
	<div class='span4' id='navigation_tree'>
		<p>Navigation tree</p>
		<ul id="sitemap">
			<?php if (isset($navigation_tree_nodes) && is_array($navigation_tree_nodes)) : 
				  foreach ($navigation_tree_nodes as $navigation_tree_node): ?>
				  <li><a href="#"><?php echo $navigation_tree_node->display_name; ?></a>
				  	<ul></ul>
				  </li>
			<!-- li><a href="#">First link</a>
				<ul>
					<li><a href="#">First link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Second link</a></li>
					<li><a href="#">Third link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Fourth link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Fifth link</a></li>
				</ul>					
			</li>
			<li><a href="#">Second link</a>
				<ul>
					<li><a href="#">First link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Second link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Third link</a></li>
					<li><a href="#">Fourth link</a></li>
					<li><a href="#">Fifth link</a></li>
				</ul>					
			</li>
			<li><a href="#">Third link</a>
				<ul>
					<li><a href="#">First link</a></li>
					<li><a href="#">Second link</a></li>
					<li><a href="#">Third link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Fourth link</a></li>
					<li><a href="#">Fifth link</a></li>
				</ul>					
			</li>
			<li><a href="#">Fourth link</a>
				<ul>
					<li><a href="#">First link</a></li>
					<li><a href="#">Second link</a></li>
					<li><a href="#">Third link</a></li>
					<li><a href="#">Fourth link</a></li>
					<li><a href="#">Fifth link</a></li>
				</ul>					
			</li>
			<li><a href="#">Fifth link</a>
				<ul>
					<li><a href="#">First link</a></li>
					<li><a href="#">Second link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Third link</a></li>
					<li><a href="#">Fourth link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>
					<li><a href="#">Fifth link</a></li>
				</ul>					
			</li -->
			<?php endforeach; 
			      endif; ?>
		</ul>
	</div>
	<div class="span8" id="test_specification">
		<div class='pad_l pad_r'>
			<h4>Select a node in the navigation tree</h4>
			
			<p>Different forms or details about nodes will be displayed here.</p>
		</div>
	</div>
</div>
