import {displayFlashMessageSuccess} from "../globalFunctions";
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
    $.get(url)
        .done(function (data, textStatus, jqXDR) {
            displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');

        });
}