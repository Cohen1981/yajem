function multiFilter() {
	var classNames = '.filterRow';
	var filters = $('.sda_filter');
	filters.each(function () {
		if ($(this).val())
			classNames = classNames + '.' + $(this).val();
		}
	);
	$('.filterRow').hide();
	$(classNames).show();
}