require('../../css/fileBrowser/fileBrowser.css');


function getUrlParam( paramName ) {
    var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
    var match = window.location.search.match( reParam );

    return ( match && match.length > 1 ) ? match[1] : null;
}

function returnFileUrl() {

    var funcNum = getUrlParam( 'CKEditorFuncNum' );
    var fileUrl = $(this).attr('src');


    console.log('toto');
    window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl, '');
    window.close();
}

$('.img-popover').popover({
    html: true,
    content: "test",
    trigger: 'focus',
});

//$('.js-uploaded-file').click(returnFileUrl);