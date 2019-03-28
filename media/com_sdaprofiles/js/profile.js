function addFitting() {

	var formElement = document.querySelector("#fittingForm");
	var formData = new FormData(formElement);

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
				document.getElementById('fitting_area').innerHTML = html + document.getElementById('fitting_area').innerHTML;
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdaprofiles&view=Profiles&task=addFittingAjax");
	xhttp.send(formData);
}