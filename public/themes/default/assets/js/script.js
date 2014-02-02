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
    
    // function called within template blocks
	if (window.templatecallback) {
		templatecallback();
	}

});