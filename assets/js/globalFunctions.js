export function displayFlashMessageSuccess(message, encre) {
    $('<div class="alert alert-success alert-dismissible fade show" role="alert">\n' +
        '<strong>'+ message +'</strong>\n' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
        '<span aria-hidden="true">&times;</span>\n' +
        '</button>\n' +
        '</div>').appendTo('#'+encre);
}

function displayPagination(nbPage, pageActive) {

}


