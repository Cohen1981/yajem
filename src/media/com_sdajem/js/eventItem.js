/**
 *
 * @param userId	User id of logged in User
 */
function commentRead(userId) {
	var scc = $('#sdaCommentCount');
	if (scc.hasClass("sdajem_text_red")) {
		scc.removeClass("sdajem_text_red")

		var formElement = document.querySelector("#adminForm");
		var formData = new FormData(formElement);
		formData.append('userId', userId);
		formData.set('task', 'commentRead');

		var xhttp = new XMLHttpRequest();

		xhttp.open("POST", "index.php?option=com_sdajem&view=Event&task=commentRead", true);
		xhttp.send(formData);
	}
}