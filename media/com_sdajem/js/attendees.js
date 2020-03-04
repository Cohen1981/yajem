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
				document.getElementById('attending_block').innerHTML = html;

				var planingTool = document.getElementById('planingTab');
				if (planingTool) {
					var xhttp3 = new XMLHttpRequest();
					xhttp3.onload = function () {
						if (this.readyState === 4 && this.status === 200) {
							var html = xhttp3.response;
							if (html.toString() === "error") {
								alert('An Error occured');
							} else {
								document.getElementById('planingTab').innerHTML = html;
								makeDraggable();
							}
						}
					};
					xhttp3.open("POST", "index.php?option=com_sdajem&view=Events&task=reloadPlaningAjax", true);
					xhttp3.send(formData);
				}
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdajem&view=Attendees&task=registerAttendeeAjax");
	xhttp.send(formData);
}
