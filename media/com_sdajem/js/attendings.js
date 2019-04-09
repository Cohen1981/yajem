/**
 *
 * @param status
 */
function filterStatus(status) {
	var elements;
	switch(status) {
		case "all":
			elements = document.getElementsByClassName('sdajem_attendings');
			for (let el of elements) {
				el.hidden = false;
			}
			break;
		case "nattending":
			elements = document.getElementsByClassName('sdajem_nattending');
			for (let el of elements) {
				el.hidden = false;
			}
			elements = document.getElementsByClassName('sdajem_attending');
			for (let el of elements) {
				el.hidden = true;
			}
			elements = document.getElementsByClassName('sdajem_undecided');
			for (let el of elements) {
				el.hidden = true;
			}
			break;
		default:
			elements = document.getElementsByClassName('sdajem_nattending');
			for (let el of elements) {
				el.hidden = true;
			}
			elements = document.getElementsByClassName('sdajem_attending');
			for (let el of elements) {
				el.hidden = false;
			}
			elements = document.getElementsByClassName('sdajem_undecided');
			for (let el of elements) {
				el.hidden = true;
			}
	}
}