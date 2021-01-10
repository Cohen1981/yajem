function registerAjax(toDo) {
	var planing_switch = $('#planing_switch');
	$('#attendees_switch').prop('checked', false);
	$('#attendees_switch_reload').show();
	$('#planing_switch_reload').show();
	if (planing_switch.prop('checked')) {
		planing_switch.prop('checked',false);
		var swbp = true;
	}

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
				document.getElementById('attendees_switch_Tab').innerHTML = html;
				$('#attendees_switch').prop('checked', true);
				$('#attendees_switch_reload').hide();
				$('#attendeeCounter').html($('#currentAttendeeCount').html());

				var planingTool = document.getElementById('planing_switch_Tab');
				if (planingTool) {
					var xhttp3 = new XMLHttpRequest();
					xhttp3.onload = function () {
						if (this.readyState === 4 && this.status === 200) {
							var html = xhttp3.response;
							if (html.toString() === "error") {
								alert('An Error occured');
							} else {
								document.getElementById('planing_switch_Tab').innerHTML = html;
								makeDraggable();
								if(swbp) {
									planing_switch.prop('checked',true);
								}
								$('#planing_switch_reload').hide();
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
