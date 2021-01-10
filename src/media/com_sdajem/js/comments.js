function addCommentAjax() {
	var cc = parseInt(document.getElementById('sdaCommentCount').innerHTML);

	if(document.getElementById('comment').value == "")
	{
		alert('Kommentar Feld darf nicht leer sein');
		document.getElementById('comment').focus();
	}
	else {
		document.getElementById('sdajem_comment_button').hidden=true;
		document.getElementById('comments_switch_reload').hidden=false;
		var formElement = document.querySelector("#commentForm");
		var formData = new FormData(formElement);

		document.getElementById('comment').value = "";

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
					document.getElementById('sdajem_comment_area').innerHTML = html + document.getElementById('sdajem_comment_area').innerHTML;
				}
				document.getElementById('sdajem_comment_button').hidden=false;
				document.getElementById('comments_switch_reload').hidden=true;
				document.getElementById('sdaCommentCount').innerHTML = cc + 1;
			}
		};

		xhttp.open("POST", "index.php?option=com_sdajem&view=Comments&task=addCommentAjax");
		xhttp.send(formData);
	}
}

function deleteCommentAjax(id) {
	var cc = parseInt(document.getElementById('sdaCommentCount').innerHTML);
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
				document.getElementById('sdajem_comment_'+id).remove();
				document.getElementById('sdaCommentCount').innerHTML = cc - 1;
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdajem&view=Comment&task=deleteCommentAjax");
	xhttp.send(formData);
}