function deleteFImage(id, forceDelete) {
    var messageContainer=document.getElementById('sdaprofiles_message_container'+id);
    var fImage = $('#sdap_fimage_'+id);
    fImage.hide();
    var formElement = document.querySelector('#adminForm');
    var formData = new FormData(formElement);
    formData.append('id', id);
    formData.append('forceDelete', forceDelete);
    formData.append('task', 'deleteFittingImage');

    var xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        if (this.readyState === 4 && this.status === 200) {
            var html = xhttp.response;
            var jsonBody = JSON.parse(html.toString());
            if (jsonBody.status === 'error') {
                messageContainer.style.color = 'red';
                messageContainer.innerText=jsonBody.htmlText;
                fImage.show();
            } else {
                messageContainer.style.color = 'green';
                messageContainer.innerText=jsonBody.htmlText;
            }
        }
    };
    xhttp.open("POST", "index.php?option=com_sdaprofiles&view=FittingImages");
    xhttp.send(formData);
}