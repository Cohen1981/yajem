function switchCheckBox(id) {
	var el = document.getElementById(id);
	if (el.checked)
	{
		if (document.getElementById('basic_switch').id != el.id) {
			document.getElementById('basic_switch').checked = false;
			document.getElementById('basic_switch_label').classList.remove('sda_active')
		}
		else {
			document.getElementById('basic_switch_label').classList.add('sda_active');
		}

		if (document.getElementById('fitting_switch').id != el.id) {
			document.getElementById('fitting_switch').checked = false;
			document.getElementById('fitting_switch_label').classList.remove('sda_active')
		}
		else {
			document.getElementById('fitting_switch_label').classList.add('sda_active')
		}

		if (document.getElementById('events_switch').id != el.id) {
			document.getElementById('events_switch').checked = false;
			document.getElementById('events_switch_label').classList.remove('sda_active')
		}
		else {
			document.getElementById('events_switch_label').classList.add('sda_active')
		}

		if (document.getElementById('preferences_switch')) {
			if (document.getElementById('preferences_switch').id != el.id) {
				document.getElementById('preferences_switch').checked = false;
				document.getElementById('preferences_switch_label').classList.remove('sda_active')
			}
			else {
				document.getElementById('preferences_switch_label').classList.add('sda_active')
			}
		}
	}
}

$(function () {
	showHideGroup();
});

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