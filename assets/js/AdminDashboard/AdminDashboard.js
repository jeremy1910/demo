import {displayFlashMessageSuccess} from "../globalFunctions";
import {displayListArticle} from "./AdminDashboard-menu-article";
import {displayListCategory} from "./AdminDashboard-menu-category";

require('../../css/admin/adminDashboard-custom.css');

$('#v-menu > a').each(function () {
    $(this).click(function (e) {
        e.preventDefault();

        $('#v-menu-tabContent > div').each(function () {
            $(this).fadeOut(100);
        });
        $('#'+$(this).attr('id')+'-content').delay(100).slideDown(50);

    })
});


$('#pills-home-tab').click(function (e)  {
    e.preventDefault();
    $.get('/getData?users')
        .done(function(data, textStatus, jqXDR) {

            console.log(data);
        });
});

$('#modalWindow').click(function (e) {
    let target = $( e.target );
    console.log(target);
    if ( !target.is('#buttonCancelDelete') && !target.is('#buttonValidDelete') && !target.is('#buttonCloseModal')) {
        e.stopPropagation();
    }

});

$('#modalValiddelete').click(function (e) {
    $('#buttonValidDelete').off('click');
});

export function eventSuppr(targetToDelete) {
    let url = $('#buttonValidDelete').attr('href');
    console.log("suppression de : " + url);

    $.get(url)
        .done(function (data, textStatus, jqXDR) {
            if (targetToDelete == 'article'){
                displayFlashMessageSuccess('Article supprimé', 'flash-message');
                displayListArticle();
            }
            else if (targetToDelete == 'category'){
                if (data[0] == true){
                    displayFlashMessageSuccess(data[1].notice[0], 'flash-message');
                    displayListCategory();
                }
                else{
                    displayFlashMessageSuccess(data[1].notice[0], 'flash-message');
                }
            }

        });
}