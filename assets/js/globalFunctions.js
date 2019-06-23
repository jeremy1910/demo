export function displayFlashMessageSuccess(type, message, encre) {

    $('<div class="alert alert-'+ type +' alert-dismissible fade show" role="alert">\n' +
        '<strong>'+ message +'</strong>\n' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
        '<span aria-hidden="true">&times;</span>\n' +
        '</button>\n' +
        '</div>').appendTo('#'+encre);
}

export function displayPagination(nbPage, pageActive, callbackOnEventPage, callbackOnEventPrevious, callbackOnEventNext, $HTMLElementPageAtive = null) {
    $('#pagination').children().remove();
    let paginationPrevious = $('    <li class="page-item">' +
        '      <a class="page-link" href="#" aria-label="Previous">' +
        '        <span aria-hidden="true">&laquo;</span>' +
        '      </a>' +
        '    </li>');

    let paginationNext = $('    <li class="page-item">' +
        '      <a class="page-link" href="#" aria-label="Next">' +
        '        <span aria-hidden="true">&raquo;</span>' +
        '      </a>' +
        '    </li>');

    let paginationLayoutHTMLElement = $(
        '<nav aria-label="Page navigation example">' +
        '  <ul class="pagination" id="paginationElement">' +
        '  </ul>' +
        '</nav>');

    paginationLayoutHTMLElement.appendTo('#pagination');
    if (pageActive <= 1) {
        paginationPrevious.addClass('disabled').appendTo('#paginationElement');
    }
    else {
        paginationPrevious.click(callbackOnEventPrevious).appendTo('#paginationElement');
    }
    if (pageActive >= nbPage) {
        paginationNext.addClass('disabled').appendTo('#paginationElement');
    }
    else {
        paginationNext.click(callbackOnEventNext).appendTo('#paginationElement');
    }
    for (let i = 1; i<=nbPage; i++){
        let paginationItem;
        if (pageActive == i){
            paginationItem = $(' <li class="page-item active" aria-current="page"><a class="page-link" href="#">'+ i +'</a></li>');
        }
        else{
            paginationItem = $(' <li class="page-item"><a class="page-link" href="#">'+ i +'</a></li>');
        }
        paginationItem.click(callbackOnEventPage);
        paginationItem.appendTo('#paginationElement');

    }
    if (pageActive > nbPage){

        $('#paginationElement li').last().addClass('active');
        $HTMLElementPageAtive.val(pageActive);
    }

    paginationNext.appendTo('#paginationElement');
}


