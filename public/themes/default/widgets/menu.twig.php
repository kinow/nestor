<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="container">
			<div class="navbar-header">
	          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-6">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	        </div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-6">
          		<ul class="nav navbar-nav">
					{{ menuitems }}
			    </ul>
			    {{ Form.open({'url': '/projects/position', 'class': 'navbar-form navbar-right form-horizontal', 'role': 'projectPosition', 'method': 'GET', 'style': 'margin: 0px;'}) }}
				{{ Theme.getProjectitems() }}
			{{ Form.close() }}
			</div>
		</div>
	</div>
</nav>