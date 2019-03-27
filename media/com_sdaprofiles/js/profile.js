function addFitting() {

	var formElement = document.querySelector("#fittingForm");
	var formData = new FormData(formElement);

	var xhttp = new XMLHttpRequest();
	xhttp.onload = function () {
		if (this.readyState === 4 && this.status === 200) {
			var html = xhttp.response;
			document.getElementById('fitting_area').innerHTML = html + document.getElementById('fitting_area').innerHTML;
		}
	};

	xhttp.open("POST", "index.php?option=com_sdaprofiles&view=Profiles&format=raw&task=addFittingAjax");
	xhttp.send(formData);
}