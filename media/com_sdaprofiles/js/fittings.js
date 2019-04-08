function addFittingAjax() {
	var formElement = document.querySelector("#fittingForm");
	var formData = new FormData(formElement);

	if (document.getElementById('sdaprofiles_input_type').value != "") {
		document.getElementById('sdaprofiles_fitting_add').hidden=true;
		document.getElementById('sdaprofiles_input_type').value = "";
		document.getElementById('sdaprofiles_input_detail').value = "";
		document.getElementById('sdaprofiles_input_length').value = "";
		document.getElementById('sdaprofiles_input_width').value = "";

		var xhttp = new XMLHttpRequest();
		xhttp.onload = function () {
			if (this.readyState === 4 && this.status === 200) {
				var html = xhttp.response;
				if (html.toString() === "error") {
					alert('An Error occured');
				} else {
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
		alert("Ausrüstungsart darf nicht leer sein");
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