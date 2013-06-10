// position project
// TODO: improve docs
var position_project = function(select) {
	var form = $(select).parent('form');
	$(form).submit();
}

$(function() {
    $('#myTab a:first').tab('show');
    
    $('#myTab a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	})
});