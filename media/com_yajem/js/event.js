function delAttachment(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(id).remove();
        }
    };
    xhttp.open("POST", '/dev/yajem?task=attachment.deleteAttachment&id='+id, true);
    xhttp.send();
}

function getIcs(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            alert(xhttp.response);
        }
    };
    xhttp.open("POST", '/dev/yajem?task=event.getIcs&id='+id+'&format=raw', true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send();
}