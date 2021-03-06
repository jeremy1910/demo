export function displayFlashMessageSuccess(type, message, encre) {

    $('#'+encre).empty();
    $('<div class="alert alert-'+ type +' alert-dismissible fade show" role="alert">\n' +
        '<strong>'+ message +'</strong>\n' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
        '<span aria-hidden="true">&times;</span>\n' +
        '</button>\n' +
        '</div>').appendTo('#'+encre);
}

export function displayPagination(nbPage, pageActive, callbackOnEventPage, callbackOnEventPrevious, callbackOnEventNext, encre, $HTMLElementPageAtive = null) {
    $('#pagination-'+encre).children().remove();
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
        '  <ul class="pagination justify-content-center" id="paginationElement-'+ encre +'">' +
        '  </ul>' +
        '</nav>');

    paginationLayoutHTMLElement.appendTo('#pagination-'+encre);
    if (pageActive <= 1) {
        paginationPrevious.addClass('disabled').appendTo('#paginationElement-'+encre);
    }
    else {
        paginationPrevious.click(callbackOnEventPrevious).appendTo('#paginationElement-'+encre);
    }
    if (pageActive >= nbPage) {
        paginationNext.addClass('disabled').appendTo('#paginationElement-'+encre);
    }
    else {
        paginationNext.click(callbackOnEventNext).appendTo('#paginationElement-'+encre);
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
        paginationItem.appendTo('#paginationElement-'+encre);

    }
    if (pageActive > nbPage){

        $('#paginationElement li').last().addClass('active');
        $HTMLElementPageAtive.val(pageActive);
    }

    paginationNext.appendTo('#paginationElement-'+encre);
}


export function collapseOnWidthScreen(elementCollapseID, width) {
    if ($(window).width() >= width) {
        $(elementCollapseID).collapse('show');
    }else {
        $(elementCollapseID).collapse('hide');
    }
    $(window).resize(function () {
        if ($(this).width() < width) {
            $(elementCollapseID).collapse('hide');
        } else {
            $(elementCollapseID).collapse('show');
        }
    });
}

export function $_GET(param) {
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if ( param ) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}