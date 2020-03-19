function multiFilter() {
	var classNames = '.filterRow';
	var filters = $('.sdap_filter');
	filters.each(function () {
		if ($(this).val())
			classNames = classNames + '.' + $(this).val();
		}
	);
	$('.filterRow').hide();
	$(classNames).show();
}