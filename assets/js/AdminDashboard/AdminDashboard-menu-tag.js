import { displayFlashMessageSuccess } from '../globalFunctions';
import {eventSuppr} from "./AdminDashboard";

import { displayFlashMessageSuccess } from '../globalFunctions';
import {eventSuppr} from "./AdminDashboard";
//import { eventSuppr } from 'AdminDashboard';

const NB_COL = 3;
const COL_WIDTH = 100/NB_COL;

$('#divAddNewTag').hide();

$('#addTagButton').click(function (e) {
    e.preventDefault();
    $('#divAddNewTag').slideToggle();
});

$('#confimAddTagButton').click(function (e) {
    e.preventDefault();
    let name = $('#filter_tag_newLibele').val();
    if (name != ''){

        $.getJSON('/addTagA?name='+name)
            .done(function (data, textStatus, jqXDR) {
                if (data[0] === true){
                    displayFlashMessageSuccess(data[1].notice, 'flash-message');
                }
            });
    }
});

$('#validateTagButton').click(function(e){
    e.preventDefault();
    displayListTag();
});



export function displayListTag() {

    let request = "";

    if ($('#filter_tag_id').val() != "")
    {
        request += '&id='+$('#filter_tag_id').val();
    }
    if ($('#filter_category_libele').val() != ""){

        request += '&id='+$('#filter_tag_tagName').val();
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
    $.getJSON('/get_info?t=tag'+request)
        .done(function (data, textStatus, jqXDR) {
            $('#table-body-tag').empty();
            $.each(data, function (i, item) {

                dashboardAdminCreateTableLineMenuTag(item);
            });
            $('.js-btn-suppr-tag').each(function () {
                $(this).click(function () {
                    $('#buttonValidDelete').attr('href', $(this).attr('href'));
                    $('#buttonValidDelete').click(function (e) {
                        e.preventDefault();
                        eventSuppr('tag');

                    });
                });
            });
            $('.js-btn-edit').each(function () {
                $(this).click(function (e) {
                    e.preventDefault();
                    let href = $(this).attr('href');
                    let num = $(this).attr('num');
                    let text = $('#tagName'+num).text();


                    $('#tagName'+num).text('');
                    let $btn = $('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>');
                    $btn.click(function (e) {
                        e.preventDefault();
                        let newLibele = $('#inputNewTag'+ num).val();
                            $.getJSON(href+'&name='+newLibele)
                                .done(function (data, textStatus, jqXDR) {
                                    if (data[0] === true){
                                        displayFlashMessageSuccess(data[1].notice, 'flash-message');
                                        displayListTag();
                                    }
                                });
                    });
                    $btn.appendTo($('<div class="col-2"></div></div>').appendTo($('<div class="row"><div class="col-10"><input id="inputNewTage'+ num +'" type="text" class="form-control" placeholder="'+ text +'"></div>').appendTo('#tageName'+num)));
                    //$('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>').appendTo('#categoryLibele'+num);
                })
            })
        })

}

function  dashboardAdminCreateTableLineMenuTag(item){

    let $t = $('#table-body-tag');

    $('<tr>' +
        '<th style="width:' + COL_WIDTH + '%" scope="row">'+ item.id +'</th>' +
        '<td style="width:' + COL_WIDTH + '%" id="tagName'+ item.id +'">'+item.tagName+'</td>' +
        '<td style="width:' + COL_WIDTH + '%" ><div class="btn-group"><a href="/edttagA?id='+ item.id +'" num="'+item.id +'" class="btn btn-secondary js-btn-edit">Modifier le nom</a>' +
        '<a href="/delTagA?id='+ item.id +'"class="btn btn-danger js-btn-suppr-category" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
        '</tr>').appendTo($t).hide().fadeIn(500);

}