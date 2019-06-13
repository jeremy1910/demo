export function displayFlashMessageSuccess(message, encre) {
    $('<div class="alert alert-success alert-dismissible fade show" role="alert">\n' +
        '<strong>'+ message +'</strong>\n' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
        '<span aria-hidden="true">&times;</span>\n' +
        '</button>\n' +
        '</div>').appendTo('#'+encre);
}

export function displayPagination(nbPage, pageActive, calbackOnEvent) {
    let paginationLayoutHTMLElement = $('<nav aria-label="Page navigation example">' +
        '  <ul class="pagination">' +
        '    <li class="page-item">' +
        '      <a class="page-link" href="#" aria-label="Previous">' +
        '        <span aria-hidden="true">&laquo;</span>' +
        '      </a>' +
        '    </li>' +
        '    <div id="paginationElement"></div>'+
        '    <li class="page-item">' +
        '      <a class="page-link" href="#" aria-label="Next">' +
        '        <span aria-hidden="true">&raquo;</span>' +
        '      </a>' +
        '    </li>' +
        '  </ul>' +
        '</nav>');
    paginationLayoutHTMLElement.appendTo('#pagination');


    for (let i = 1; i<=nbPage; i++){
        let paginationItem = $(' <li class="page-item"><a class="page-link" href="#">'+ i +'</a></li>');
        paginationItem.click(calbackOnEvent);
        paginationItem.appendTo('paginationElement');

    }
}


