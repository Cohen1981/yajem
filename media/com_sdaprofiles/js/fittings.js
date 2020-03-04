function addFittingAjax() {
	var formElement = document.querySelector("#fittingForm");
	var formData = new FormData(formElement);
	var formTask = document.getElementById('formTask').value;

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
					if (formTask === "edit") {
						document.getElementById('sdaprofiles_fitting_'+document.getElementById('sdaprofiles_fitting_id').value).remove();
					}
					document.getElementById('fitting_area').innerHTML = document.getElementById('fitting_area').innerHTML + html;
					document.getElementById('formTask').value = '';
					document.getElementById('sdaprofiles_fitting_id').value = "";
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
	var formElement = document.querySelector("#fittingForm");
	var formData = new FormData(formElement);
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

function newEquipment(id) {
	var formData = new FormData();
	formData.append('profileId', id);
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
				document.getElementById('fitting_form').innerHTML = html;
				checkType();
				document.getElementById('fittingForm').hidden = false;
				document.getElementById('formTask').value = 'add';
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdaprofiles&format=raw&view=Fittings&task=add");
	xhttp.send(formData);
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
				document.getElementById('fitting_form').innerHTML = html;
				checkType();
				document.getElementById('fittingForm').hidden = false;
				document.getElementById('formTask').value = 'edit';
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdaprofiles&format=raw&view=Fittings&task=edit");
	xhttp.send(formData);
}

function checkType() {
	var id = 'ft_' + document.getElementById('sdaprofiles_input_type').value;
	var needSpace = parseInt(document.getElementById(id).value);
	$("[id^=fitting_images_]").hide();

	var el = $('#fitting_images_'+document.getElementById('sdaprofiles_input_type').value);

	if (el) {
		//if (needSpace === 1)
			el.show();
		//else
		//	el.hide();
	}

	document.getElementById('fitting_length').hidden = !(needSpace === 1);
	document.getElementById('fitting_width').hidden = !(needSpace === 1);
}

$(function () {
	checkType();
});