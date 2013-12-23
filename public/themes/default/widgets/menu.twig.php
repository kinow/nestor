<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			<div class="nav-collapse">
				<ul class="nav">
					{{ menuitems }}
			    </ul>
			</div><!--/.nav-collapse -->
			<div class='nav-collapse text-right'>
				<form method="get" action="{$base_url}projects/position" style="margin: 0px;">
					{{ projectitems }}
				</form>
			</div>
		</div>
	</div>
</div>