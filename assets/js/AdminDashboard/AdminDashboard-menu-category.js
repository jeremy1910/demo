import { displayFlashMessageSuccess } from '../globalFunctions';
$('#divAddNewCategory').hide();

$('#addCategoryButton').click(function (e) {
    e.preventDefault();
    $('#divAddNewCategory').slideToggle();
});

$('#confimAddCategoryButton').click(function (e) {
    e.preventDefault();
    let libele = $('#filter_category_newLibele').val();
    if (libele != ''){

        $.getJSON('/addCategoryA?libele='+libele)
            .done(function (data, textStatus, jqXDR) {
                if (data[0] === true){
                    displayFlashMessageSuccess(data[1].notice, 'flash-message');
                }
            });
    }
});

$('#validateCategoryButton').click(displayListArticle);

function displayListArticle(e) {
    e.preventDefault();
    let request = "";

    if ($('#filter_category_id').val() != "")
    {
        request += '&id='+$('#filter_category_id').val();
    }
    if ($('#filter_category_libele').val() != ""){

        request += '&id='+$('#filter_category_libele').val();
    }


/*
    if ($('#article_dashboard_filter_created_before').val() != ''){
        request += '&created_before=' + $('#article_dashboard_filter_created_before').val();
    }
    if ($('#article_dashboard_filter_created_after').val() != ''){
        request += '&created_after=' + $('#article_dashboard_filter_created_after').val();
    }
    console.log(request);
*/
    $.getJSON('/get_info?t=category'+request)
        .done(function (data, textStatus, jqXDR) {
            $('#table-body-category').empty();
            $.each(data, function (i, item) {

                dashboardAdminCreateTableLineMenuCategory(item);
            });
            $('.js-btn-suppr').each(function () {
                $(this).click(function () {
                    hrefArtcileToDelete = $(this).attr('href');
                });
            });
        })

}

function  dashboardAdminCreateTableLineMenuCategory(item){

    let $t = $('#table-body-category');

    $('<tr><th>'+ item.id +'</th>' +
        '<td>'+item.libele+'</td>' +
        '<td><a href="/delete/'+ item.id +'" class="btn btn-danger js-btn-suppr" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
        '</tr>').appendTo($t).hide().fadeIn(500);

}