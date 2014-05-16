// position project
// TODO: improve docs
var position_project = function(select) {
	var form = $(select).parent('form');
	$(form).submit();
}

var get_children = function(node_id, node_type_id) {
	$('#nodes_panel').html('<h1>oi</h1>');
}

$(function() {
  $('#myTab a:first').tab('show');
    
  $('#myTab a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	});

  $('[data-toggle="tooltip"]').tooltip();

  // function called within template blocks
	if (window.templatecallback) {
		templatecallback();
	}

});

// http://stackoverflow.com/questions/14062368/new-recommended-jquery-templates
String.prototype.format = function() {
  var args = arguments;
  return this.replace(/{(\d+)}/g, function(match, number) { 
    return typeof args[number] != 'undefined'
      ? args[number]
      : match
    ;
  });
};

// Create a YUI sandbox on your page.
YUI_config = {
    debug: true,
    combine: true
    //comboBase: 'http://mydomain.com/combo?',
    //root: 'yui3/'
};

YUI().use('node', 'event', function (Y) {
});