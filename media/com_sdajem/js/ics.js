function getIcs(id) {
	var xhttp = new XMLHttpRequest();
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
	xhttp.open("POST", 'index.php?&option=com_sdajem&format=raw&view=Event&task=getIcsAjax&id=' + id, true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send();
}