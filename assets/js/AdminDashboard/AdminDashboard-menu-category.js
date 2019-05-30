import { displayFlashMessageSuccess } from '../globalFunctions';
import {eventSuppr} from "./AdminDashboard";
//import { eventSuppr } from 'AdminDashboard';

const NB_COL = 3;
const COL_WIDTH = 100/NB_COL;

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

$('#validateCategoryButton').click(function(e){
    e.preventDefault();
    displayListCategory();
});



export function displayListCategory() {

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
            $('.js-btn-suppr-category').each(function () {
                $(this).click(function () {
                    $('#buttonValidDelete').attr('href', $(this).attr('href'));
                    $('#buttonValidDelete').click(function (e) {
                        e.preventDefault();
                        eventSuppr('category');

                    });
                });
            });
            $('.js-btn-edit').each(function () {
                $(this).click(function (e) {
                    e.preventDefault();
                    let href = $(this).attr('href');
                    let num = $(this).attr('num');
                    let text = $('#categoryLibele'+num).text();


                    $('#categoryLibele'+num).text('');
                    let $btn = $('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>');
                    $btn.click(function (e) {
                        e.preventDefault();
                        let newLibele = $('#inputNewCategory'+ num).val();
                            $.getJSON(href+'&libele='+newLibele)
                                .done(function (data, textStatus, jqXDR) {
                                    if (data[0] === true){
                                        displayFlashMessageSuccess(data[1].notice, 'flash-message');
                                        displayListCategory();
                                    }
                                });
                    });
                    $btn.appendTo($('<div class="col-2"></div></div>').appendTo($('<div class="row"><div class="col-10"><input id="inputNewCategory'+ num +'" type="text" class="form-control" placeholder="'+ text +'"></div>').appendTo('#categoryLibele'+num)));
                    //$('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>').appendTo('#categoryLibele'+num);
                })
            })
        })

}

function  dashboardAdminCreateTableLineMenuCategory(item){

    let $t = $('#table-body-category');

    $('<tr>' +
        '<th style="width:' + COL_WIDTH + '%" scope="row">'+ item.id +'</th>' +
        '<td style="width:' + COL_WIDTH + '%" id="categoryLibele'+ item.id +'">'+item.libele+'</td>' +
        '<td style="width:' + COL_WIDTH + '%" ><div class="btn-group"><a href="/edtCategoryA?id='+ item.id +'" num="'+item.id +'" class="btn btn-secondary js-btn-edit">Modifier le libélé</a>' +
        '<a href="/rmCategoryA?id='+ item.id +'"class="btn btn-danger js-btn-suppr-category" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
        '</tr>').appendTo($t).hide().fadeIn(500);

}