function addCommentAjax() {
	document.getElementById('sdajem_comment_button').hidden=true;
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
		}
	};

	xhttp.open("POST", "index.php?option=com_sdajem&view=Comments&task=addCommentAjax");
	xhttp.send(formData);
}

function deleteCommentAjax(id) {
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
			}
		}
	};

	xhttp.open("POST", "index.php?option=com_sdajem&view=Comment&task=deleteCommentAjax");
	xhttp.send(formData);
}