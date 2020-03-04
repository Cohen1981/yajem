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