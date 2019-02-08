
function delAttachment(id) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
			document.getElementById(id).remove();
		}
	};
	xhttp.open("POST", 'index.php?option=com_yajem&task=attachment.deleteAttachment&id=' + id, true);
	xhttp.send();
}

function delComment(id) {
	var xhttp = new XMLHttpRequest();
	var commentCount = document.getElementById('comment_count').innerText - 1;
	xhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
			if (document.getElementById('avatar_' + id)) {
				document.getElementById('avatar_' + id).remove();
			}

			document.getElementById('output_' + id).remove();
			document.getElementById('comment_count').innerText = commentCount;
		}
	};
	xhttp.open("POST", 'index.php?option=com_yajem&task=event.deleteComment&id=' + id, true);
	xhttp.send();
}

function getIcs(id) {
	var xhttp = new XMLHttpRequest();
	var task = 'task=event.getIcs';
	xhttp.onload = function () {
		if (this.readyState === 4 && this.status === 200) {
			// Try to find out the filename from the content disposition `filename` value
			var disposition = xhttp.getResponseHeader('content-disposition');
			var matches = /"([^"]*)"/.exec(disposition);
			var filename = (matches != null && matches[1] ? matches[1] : 'invite.ics');

			// The actual download
			var blob = new Blob([xhttp.response], { type: 'text/calendar' });
			var link = document.createElement('a');
			link.href = window.URL.createObjectURL(blob);
			link.download = filename;

			document.body.appendChild(link);

			link.click();

			document.body.removeChild(link);
		}
	};
	xhttp.open("POST", 'index.php?format=raw&option=com_yajem&task=event.getIcs&id=' + id, true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(task);
}

function comment() {

	var formElement = document.querySelector("#commentForm");
	var formData = new FormData(formElement);
	var commentCount = (parseInt(document.getElementById('comment_count').innerText) + parseInt(1));

	var xhttp = new XMLHttpRequest();
	xhttp.onload = function () {
		if (this.readyState === 4 && this.status === 200) {
			var html = xhttp.response;
			document.getElementById('yajem_comment_grid').innerHTML = html + document.getElementById('yajem_comment_grid').innerHTML;
			document.getElementById('comment_count').innerText = commentCount;
			document.getElementById('comment').innerText = "";
			document.getElementById('comment').value = "";
		}
	};

	xhttp.open("POST", 'index.php?format=raw&option=com_yajem&task=event.saveComment', true);
	xhttp.send(formData);
}

function changeAttending(task) {
	document.getElementById('reg_buttons').innerHTML = "";
	var eventId = document.getElementById('attendees_eventId').value;
	var attendeesId = document.getElementById('attendees_id').value;
	var userId = document.getElementById('attendees_userId').value;
	var params = 'task=event.changeAttendingStatus&eventId=' + eventId + '&register=' + task + '&id=' + attendeesId;
	var params2 = 'task=event.getRegButtons&eventId=' + eventId + '&userId=' + userId;
	var xhttp = new XMLHttpRequest();
	var xhttp2 = new XMLHttpRequest();
	xhttp.onload = function () {
		if (this.readyState === 4 && this.status === 200) {
			var html = xhttp.response;

			if (document.getElementById('attendee_' + userId))
			{
				document.getElementById('attendee_' + userId).remove();
			}

			document.getElementById('yajem_attendees').insertAdjacentHTML('beforeend', html);
		}

		xhttp2.onload = function () {
			if (this.readyState === 4 && this.status === 200) {
				var html = xhttp2.response;
				document.getElementById('reg_buttons').innerHTML = html;
			}
		}
		xhttp2.open("POST", 'index.php?format=raw&option=com_yajem&task=event.getRegButtons', true);
		xhttp2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhttp2.send(params2);
	};
	xhttp.open("POST", 'index.php?format=raw&option=com_yajem&task=event.changeAttendingStatus', true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(params);
}

function switchEventStatus(status) {
	var xhttp = new XMLHttpRequest();
	var params = 'task=event.changeEventStatus&eStatus=' + status + '&eventId=' + document.getElementById('YajemEventId').value;
	xhttp.onload = function () {
		if (this.readyState === 4 && this.status === 200) {
			var html = xhttp.response;
			if (status === 'confirm') {
				document.getElementById('statusConfirmed').setAttribute('class','far fa-thumbs-up');
				document.getElementById('statusCanceled').setAttribute('class', 'yajem_hidden');
				document.getElementById('statusPending').setAttribute('class', 'yajem_hidden');
			}
			else {
				document.getElementById('statusConfirmed').setAttribute('class','yajem_hidden');
				document.getElementById('statusCanceled').setAttribute('class', 'far fa-thumbs-down');
				document.getElementById('statusPending').setAttribute('class', 'yajem_hidden');
			}

			document.getElementById('eventStatusLink').innerHTML = html;
		}
	};
	xhttp.open("POST", 'index.php?format=raw&option=com_yajem&task=event.changeEventStatus', true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(params);
}

function switchClass(labelId) {
	var iconClass = document.getElementById(labelId).firstElementChild.getAttribute('class');
	if (iconClass === 'far fa-minus-square') {
		document.getElementById(labelId).firstElementChild.setAttribute('class', 'far fa-plus-square');
	} else {
		document.getElementById(labelId).firstElementChild.setAttribute('class', 'far fa-minus-square');
	}
}
