function changeEventStatus(status, eventId) {
	var formData = new FormData();
	formData.append('id', eventId);
	formData.append('status', status);

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
				document.getElementById('eventStatus_'+eventId).innerHTML = html;
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdajem&view=Events&task=changeEventStatusAjax");
	xhttp.send(formData);
}