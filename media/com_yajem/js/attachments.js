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