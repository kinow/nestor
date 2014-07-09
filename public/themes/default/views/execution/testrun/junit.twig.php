{% block content %}
<script src="{{ Theme.asset.url('js/plugins/codemirror/lib/codemirror.js') }}"></script>
<link rel="stylesheet" href="{{ Theme.asset.url('js/plugins/codemirror/lib/codemirror.css') }}"></script>
<script src="{{ Theme.asset.url('js/plugins/codemirror/mode/xml/xml.js') }}"></script>
<div class='row'>
	<div class='col-xs-12'>
		<p><strong>JUnit XML output</strong></p>
		<div class='form-group'>
			<textarea id='junit-xml' class='form-control' rows='12'>{{ document }}</textarea>
		</div>
		<div class='form-group'>
		{{ Form.open({'url': URL.current(), 'method': 'get'}) }}
			<input type='hidden' name='download' value='true' />
			<input type='submit' class='btn btn-primary' value='Download XML' />
		{{ Form.close() }}
		</div>
	</div>
</div>
<script type='text/javascript'>
function templatecallback(Y) {
	var editor = CodeMirror.fromTextArea(Y.one('#junit-xml').getDOMNode(),
	{
		mode: "xml",
		lineNumbers: true,
	});
}
</script>
{% endblock %}