function registerAjax(toDo) {
	var formElement = document.querySelector("#attendeeForm");
	var formData = new FormData(formElement);
	document.getElementById('registerButtons').hidden=true;
	formData.append('action', toDo);
	if (document.getElementById('attendeeId'))
		var attendeeId=document.getElementById('attendeeId').value;

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
				let count = parseInt(document.getElementById('attendeeCount').innerText);
				if (document.getElementById('attendee'+attendeeId)) {
					document.getElementById('attendee' + attendeeId).remove();
				}
				if (parseInt(toDo) === 1)
					document.getElementById('attendeeCount').innerText = count+=1;
				else
					document.getElementById('attendeeCount').innerText = count-=1;
				document.getElementById('sdajem_attendee_area').innerHTML = html + document.getElementById('sdajem_attendee_area').innerHTML;

				var xhttp2 = new XMLHttpRequest();
				xhttp2.onload = function () {
					if (this.readyState === 4 && this.status === 200) {
						var html = xhttp2.response;
						if (html.toString() === "error") {
							alert('An Error occured');
						} else {
							document.getElementById('registerButtons').innerHTML = html;
							document.getElementById('registerButtons').hidden=false;
						}
					}
				};
				xhttp2.open("POST", "index.php?option=com_sdajem&view=Events&task=getRegisterHtmlAjax");
				xhttp2.send(formData);
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdajem&view=Attendees&task=registerAttendeeAjax");
	xhttp.send(formData);
}
