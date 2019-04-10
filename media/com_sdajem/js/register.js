/**
 *
 * @param status
 */
function subscribeAjax(status) {
	var formElement = document.querySelector("#attendeeForm");
	var formData = new FormData(formElement);
	formData.append('subscribed', status);

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
				document.getElementById('subscribeButtons').innerHTML = html;
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdajem&view=Events&task=subscribeAjax");
	xhttp.send(formData);
}