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