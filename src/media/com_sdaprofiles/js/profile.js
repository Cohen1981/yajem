$(function () {
	disableUser();
	showHideGroup();
});

function disableUser() {
	var user = document.getElementById('users_user_id');
	var userId = user.options[user.selectedIndex].value;
	if (userId !== '') {
		document.getElementById('users_user_id').setAttribute('disabled', 'true');
	}
}

function showHideGroup() {
	var bool = parseInt(document.getElementById('groupProfile').value) === 1;
	upTo(document.getElementById('userName'), 'control-group').hidden = !bool;
	upTo(document.getElementById('defaultGroup'), 'control-group').hidden = !bool;

	// Delete following line if you want to set groupProfile manually
	upTo(document.getElementById('groupProfile'), 'control-group').hidden = true;
	upTo(document.getElementById('users_user_id'), 'control-group').hidden = bool;
	upTo(document.getElementById('address1'), 'control-group').hidden = bool;
	upTo(document.getElementById('address2'), 'control-group').hidden = bool;
	upTo(document.getElementById('city'), 'control-group').hidden = bool;
	upTo(document.getElementById('postal'), 'control-group').hidden = bool;
	upTo(document.getElementById('phone'), 'control-group').hidden = bool;
	upTo(document.getElementById('mobil'), 'control-group').hidden = bool;
	upTo(document.getElementById('dob'), 'control-group').hidden = bool;
	upTo(document.getElementById('mailOnNew'), 'control-group').hidden = bool;
	upTo(document.getElementById('mailOnEdited'), 'control-group').hidden = bool;
}

function upTo(el, tagName) {
	// tagName = tagName.toLowerCase();

	while (el && el.parentNode) {
		el = el.parentNode;
		if (el.className && el.className.toString().trim() === tagName.toString()) {
			return el;
		}
	}

	// Many DOM methods return null if they don't
	// find the element they are searching for
	// It would be OK to omit the following and just
	// return undefined
	return null;
}