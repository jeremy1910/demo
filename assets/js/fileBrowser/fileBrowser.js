require('../../css/fileBrowser/fileBrowser.css');

$(document).ready(function () {
    function getUrlParam( paramName ) {
        var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
        var match = window.location.search.match( reParam );

        return ( match && match.length > 1 ) ? match[1] : null;
    }

    function returnFileUrl($this) {

        var funcNum = getUrlParam( 'CKEditorFuncNum' );
        var fileUrl = $($this).attr('href');



        window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl, '');
        window.close();
    }



    $('.js-popover-filbrowser').on('inserted.bs.popover', function () {
        $('.js-select-img').click(function (e) {
            e.preventDefault();
            returnFileUrl(this);
        });

        $('.js-delete-img').click(function (e) {
            e.preventDefault();

            $('#img-'+$(this).attr("href")).toggleClass('collapsed');


        });

    });


});



//$('.js-uploaded-file').click(returnFileUrl);