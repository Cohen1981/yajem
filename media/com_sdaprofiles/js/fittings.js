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
					if (document.getElementById('formTask').value === "edit") {
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

function newEquipment() {
	document.getElementById('sdaprofiles_input_type').value = "";
	document.getElementById('sdaprofiles_input_detail').value = "";
	document.getElementById('sdaprofiles_input_length').value = "";
	document.getElementById('sdaprofiles_input_width').value = "";
	document.getElementById('sdaprofiles_fitting_id').value = "";
	$('[name="image"]').removeAttr('checked');
	document.getElementById('formTask').value = 'new';
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
				$('#sdaprofiles_input_type').val(equipment.type).change;
				$("input:radio[name='image'][value='"+equipment.image+"']").attr('checked', 'checked');
				document.getElementById('sdaprofiles_input_detail').value = equipment.detail;
				document.getElementById('sdaprofiles_input_length').value = equipment.length;
				document.getElementById('sdaprofiles_input_width').value = equipment.width;
				document.getElementById('sdaprofiles_fitting_id').value = equipment.sdaprofiles_fitting_id;
				document.getElementById('formTask').value = 'edit';
				document.getElementById('fittingForm').hidden = false;
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdaprofiles&format=json&view=Fittings&task=read");
	xhttp.send(formData);
}

function checkType() {
	var id = 'ft_' + document.getElementById('sdaprofiles_input_type').value;
	var needSpace = parseInt(document.getElementById(id).value);
	$("[id^=fitting_images_]").hide();

	var el = $('#fitting_images_'+document.getElementById('sdaprofiles_input_type').value);

	if (el) {
		if (needSpace === 1)
			el.show();
		else
			el.hide();
	}

	document.getElementById('fitting_length').hidden = !(needSpace === 1);
	document.getElementById('fitting_width').hidden = !(needSpace === 1);
}

$(function () {
	checkType();
});