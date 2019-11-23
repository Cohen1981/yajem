function filter(elName) {
	var el = document.getElementById(elName);
	var className = el.options[el.selectedIndex].value;
	var rows = document.getElementsByClassName('filterRow');
	if (className === 'all') {
		$('.filterRow').show();
	}
	else {
		$('.filterRow').hide();
		$('.' + className).show();
	}
}