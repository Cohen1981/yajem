$(
	function () {
		showRegistrationFields();
		switchCalendar();
	}
)

function switchCalendar() {
	if (parseInt(document.getElementById('allDayEvent').value) === 1) {
		document.getElementById('startDateTime_btn').setAttribute('data-show-time', '0');
		document.getElementById('startDateTime_btn').setAttribute('data-dayformat', '%d.%m.%Y');
		JoomlaCalendar.init(upTo(document.getElementById('startDateTime'), 'field-calendar'));
		document.getElementById('endDateTime_btn').setAttribute('data-show-time', '0');
		document.getElementById('endDateTime_btn').setAttribute('data-dayformat', '%d.%m.%Y');
		JoomlaCalendar.init(upTo(document.getElementById('endDateTime'), 'field-calendar'));
	}
	else
	{
		document.getElementById('startDateTime_btn').setAttribute('data-show-time', '1');
		document.getElementById('startDateTime_btn').setAttribute('data-dayformat', '%d.%m.%Y %H:%M');
		JoomlaCalendar.init(upTo(document.getElementById('startDateTime'), 'field-calendar'));
		document.getElementById('endDateTime_btn').setAttribute('data-show-time', '1');
		document.getElementById('endDateTime_btn').setAttribute('data-dayformat', '%d.%m.%Y %H:%M');
		JoomlaCalendar.init(upTo(document.getElementById('endDateTime'), 'field-calendar'));
	}
}

function showRegistrationFields() {
	var reg = parseInt(document.getElementById('useRegistration').value);
	if (reg === 0) {
		upTo(document.getElementById('registerUntil'),'control-group').hidden = true;
		upTo(document.getElementById('registrationLimit'),'control-group').hidden = true;
		//upTo(document.getElementById('useWaitingList'),'control-group').hidden = true;
	}
	else {
		upTo(document.getElementById('registerUntil'),'control-group').hidden = false;
		upTo(document.getElementById('registrationLimit'),'control-group').hidden = false;
		//upTo(document.getElementById('useWaitingList'),'control-group').hidden = false;
	}
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