function switchCheckBox(id) {
	var el = document.getElementById(id);
	if (el.checked)
	{
		if (document.getElementById('event_switch').id != el.id) {
			document.getElementById('event_switch').checked = false;
			document.getElementById('event_switch_label').classList.remove('sda_active')
		}
		else {
			document.getElementById('event_switch_label').classList.add('sda_active');
		}

		if (document.getElementById('location_switch')) {
			if (document.getElementById('location_switch').id != el.id) {
				document.getElementById('location_switch').checked = false;
				document.getElementById('location_switch_label').classList.remove('sda_active')
			}
			else {
				document.getElementById('location_switch_label').classList.add('sda_active')
			}
		}

		if (document.getElementById('attendees_switch')) {
			if (document.getElementById('attendees_switch').id != el.id) {
				document.getElementById('attendees_switch').checked = false;
				document.getElementById('attendees_switch_label').classList.remove('sda_active')
			}
			else {
				document.getElementById('attendees_switch_label').classList.add('sda_active')
			}
		}

		if (document.getElementById('comments_switch')) {
			if (document.getElementById('comments_switch').id != el.id) {
				document.getElementById('comments_switch').checked = false;
				document.getElementById('comments_switch_label').classList.remove('sda_active')
			}
			else {
				document.getElementById('comments_switch_label').classList.add('sda_active')
			}
		}

		if (document.getElementById('planing_switch')) {
			if (document.getElementById('planing_switch').id != el.id) {
				document.getElementById('planing_switch').checked = false;
				document.getElementById('planing_switch_label').classList.remove('sda_active')
			}
			else {
				document.getElementById('planing_switch_label').classList.add('sda_active')
			}
		}
	}
}