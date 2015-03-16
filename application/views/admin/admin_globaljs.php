<script type="text/javascript">
$(document).ready(function () {
    $("input#quickSearchCamera").keyup( function(event) {
        event.preventDefault();
    	var code = event.keyCode || event.which;
		if ( code == 13 ) {
			var searchValue = $(this).val();
			//if(searchValue == "") { alert("Please input Keywords!"); return;}
			$("form#quickSearchForm").attr("action", "/admin/home/quickSearchCamera");
			
			$("form#quickSearchForm").submit();
		}
	});
});
function successAlert(msg) {
    new PNotify({
        title: msg,
        text: '',
        type: 'success'
    });
}
function errorAlert(msg) {
    new PNotify({
        title: msg,
        text: '',
        type: 'error'
    });
}
function infoAlert(msg) {
    new PNotify({
        title: msg,
        text: '',
        type: 'info'
    });
}
</script>