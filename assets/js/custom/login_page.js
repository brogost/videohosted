$(document).ready(function () {
	$("input[name='tecSupport']").change(function () {
		if ($(this).is(':checked')) {
			$("textarea[name='tecDescription']").show();
			$("textarea[name='tecDescription']").parent().removeClass("hide");
		} else {
			$("textarea[name='tecDescription']").hide();
			$("textarea[name='tecDescription']").parent().addClass("hide");
		}
	});
});