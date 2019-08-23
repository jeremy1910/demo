import {eventSuppr} from "./AdminDashboard";
import {collapseOnWidthScreen, displayFlashMessageSuccess, displayPagination} from '../globalFunctions';

const NB_COL = 3;
const COL_WIDTH = 100/NB_COL;

$(document).ready(function () {

    $('#divAddNewTag').hide();

    $('#addTagButton').click(function (e) {
        e.preventDefault();
        $('#divAddNewTag').slideToggle();
    });

    $('#selecter-tag').selectpicker().change(function () {
        $('#tag_filter_nbResult').val('');

        $('#tag_filter_nbResult').val($("#selecter-tag option:selected").text());
        menuTagSendAjaxFormFilter();
    });

    $("form[name='tag_filter']").submit(function (e) {
        console.log('toto');
        e.preventDefault();
        menuTagSendAjaxFormFilter();

    });

    $('#btn-create-new-tag').click(function (e) {
        e.preventDefault();
        if ($('#input-create-new-tag').val() != '')
        {
            $.get('/addTagA?name='+$('#input-create-new-tag').val())
                .done(function (data, textStatus, jqXDR) {
                    if (data[0] === true){
                        displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                        menuTagSendAjaxFormFilter()
                    }
                    else{
                        displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                    }
                });
        }

    });

    collapseOnWidthScreen('#menu-tag-collapse-form', 768);

    function menuTagSendAjaxFormFilter() {
        let $form = $("form[name='tag_filter']");
        let tableBodyCategoryHeight = $('#table-body-tag').css('height') === "0px" ? 150 : $('#table-body-tag').css('height');
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $('#table-body-tag').fadeOut(400, function () {
                    $('#spinnerLoadingGeneralSearch-adminDashboardTag').addClass('d-block');
                    $('#spinnerLoadingGeneralSearch-adminDashboardTag .loading-medium').css('height', tableBodyCategoryHeight)
                    $('#spinnerLoadingGeneralSearch-adminDashboardTag').show();
                });

            }
        })
            .done(function (data) {
                $('#spinnerLoadingGeneralSearch-adminDashboardTag').removeClass('d-block');
                $('#spinnerLoadingGeneralSearch-adminDashboardTag').hide();
                $('#table-body-tag').show()
                menuTagDisplayResult(data)
            });
    }

    function menuTagDisplayResult(data) {
        let pageActive = $('#tag_filter_pageSelected').val();
        let result = JSON.parse(data);

        $('#table-body-tag').empty();
        $.each(result.result, function (i, item) {
            menuTagCreateTableLine(item);

        });
        $('.js-btn-suppr-tag').each(function () {
            $(this).click(function () {
                $('#buttonValidDelete').attr('href', $(this).attr('href'));
                $('#buttonValidDelete').click(function (e) {
                    e.preventDefault();
                    eventSuppr('tag');

                    menuTagSendAjaxFormFilter();

                });

            });
        });
        $('.js-btn-edit-tag').each(function () {
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
                                displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                                menuTagSendAjaxFormFilter();
                            }
                        });
                });
                $btn.appendTo($('<div class="col-2"></div></div>').appendTo($('<div class="row"><div class="col-10"><input id="inputNewTag'+ num +'" type="text" class="form-control" placeholder="'+ text +'"></div>').appendTo('#tagName'+num)));
            })
        });


        displayPagination(result.nbPage, pageActive, function (e) {
            e.preventDefault();
            $('#tag_filter_pageSelected').val($(this).children().text());
            menuTagSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#tag_filter_pageSelected').val(Number(pageActive) - 1);
            menuTagSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#tag_filter_pageSelected').val(Number(pageActive) + 1);
            menuTagSendAjaxFormFilter();
        }, 'tag', $('#tag_filter_pageSelected'))
    }


    function menuTagCreateTableLine(item) {
        console.log(item);
        let $t = $('#table-body-tag');

        $('<tr>' +
            '<th style="width:' + COL_WIDTH + '%" scope="row">' + item.id + '</th>' +
            '<td style="width:' + COL_WIDTH + '%" id="tagName' + item.id + '">' + item.tag_name + '</td>' +
            '<td class="d-none d-md-table-cell" style="width:' + COL_WIDTH + '%" ><div class="btn-group"><a href="/edtTagA?id=' + item.id + '" num="' + item.id + '" class="btn btn-secondary js-btn-edit-tag">Modifier le nom</a>' +
            '<a href="/delTagA?id=' + item.id + '"class="btn btn-danger js-btn-suppr-tag" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>' +
            '<td class="d-md-none">' +
            '                    <button class="btn " type="button" data-toggle="collapse" data-target="#lineTargetCollapse-'+ item.id +'" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">' +
            '                        <span class="custom_background-btn navbar-toggler-icon"></span>' +
            '                    </button>' +
            '</td>'+
            '</tr>'+
            '<tr class="d-md-none">'+
            ' <td id="lineTargetCollapse-'+ item.id +'" colspan="4" class="collapse hide"><div class="btn-group"><a href="/edtTagA?id=' + item.id + '" num="' + item.id + '" class="btn btn-secondary js-btn-edit-tag">Modifier le nom</a>' +
            '<a href="/delTagA?id=' + item.id + '"class="btn btn-danger js-btn-suppr-tag" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>' +
            '</tr>'


        ).appendTo($t).hide().fadeIn(500);

    }

});


/*

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
*/

