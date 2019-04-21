function addFittingAjax() {
	var formElement = document.querySelector("#fittingForm");
	var formData = new FormData(formElement);

	if (parseInt(document.getElementById('sdaprofiles_input_type').value) !== 0) {
		document.getElementById('sdaprofiles_fitting_add').hidden=true;

		var xhttp = new XMLHttpRequest();
		xhttp.onload = function () {
			if (this.readyState === 4 && this.status === 200) {
				var html = xhttp.response;
				if (html.toString() === "error") {
					alert('An Error occured');
				} else {
					document.getElementById('sdaprofiles_input_type').value = "";
					document.getElementById('sdaprofiles_input_detail').value = "";
					document.getElementById('sdaprofiles_input_length').value = "";
					document.getElementById('sdaprofiles_input_width').value = "";
					document.getElementById('fittingForm').hidden = true;
					document.getElementById('fitting_area').innerHTML = document.getElementById('fitting_area').innerHTML + html;
				}
				document.getElementById('sdaprofiles_fitting_add').hidden = false;
			}
		};

		xhttp.open("POST", "index.php?option=com_sdaprofiles&view=Fittings&task=addFittingAjax");
		xhttp.send(formData);
	}
	else {
		document.getElementById('sdaprofiles_input_type').focus();
		alert(document.getElementById('sdaprofilesTypeError').value);
	}
}

function deleteFittingAjax(id) {
	var formData = new FormData();
	formData.append('id', id);

	var xhttp = new XMLHttpRequest();
	xhttp.onload = function () {
		if (this.readyState === 4 && this.status === 200) {
			var html = xhttp.response;
			if (html.toString() === "error")
			{
				alert('An Error occured');
			}
			else
			{
				document.getElementById('sdaprofiles_fitting_'+id).remove();
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdaprofiles&view=Fittings&task=deleteFittingAjax");
	xhttp.send(formData);
}

function newEquipment() {
	document.getElementById('fittingForm').hidden = false;
}

function editFittingAjax(id) {
	var formData = new FormData();
	formData.append('id', id);

	var xhttp = new XMLHttpRequest();
	xhttp.onload = function () {
		if (this.readyState === 4 && this.status === 200) {
			var html = xhttp.response;
			if (html.toString() === "error")
			{
				alert('An Error occured');
			}
			else
			{
				var equipment = JSON.parse(xhttp.response);
				document.getElementById('sdaprofiles_input_detail').value = equipment.detail;
				document.getElementById('sdaprofiles_input_length').value = equipment.length;
				document.getElementById('sdaprofiles_input_width').value = equipment.width;
				document.getElementById('sdaprofiles_fitting_id').value = equipment.sdaprofiles_fitting_id;
				document.getElementById('fittingForm').hidden = false;
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdaprofiles&format=json&view=Fittings&task=read");
	xhttp.send(formData);
}