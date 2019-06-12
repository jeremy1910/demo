import { eventSuppr } from './AdminDashboard.js';
import { displayFlashMessageSuccess } from '../globalFunctions';

$(document).ready(function () {

    let menuArticleTagPrototype = $('#article_filter_tags').attr('data-prototype');
    menuArticleTagPrototype = $(menuArticleTagPrototype).find('input').prop('outerHTML');

    let menuArticleInputToAddTag = $('<div class="form-group"><label for="genericTagInput">Tags</label><input type="text" id="genericTagInput" placeholder="Ajouter un tag à rechercher" class="form-control"></div><div id="inputsTagHiden"></div>')
    $('#article_filter_tags').parent().replaceWith(menuArticleInputToAddTag);


    $('#genericTagInput').keypress(function (e) {
        if (e.key == 'Enter') {
            menuArticleCreateTagHTML($(this).val());
            $(this).val('');

        }
    });
    $("form[name='article_filter']").submit(function (e) {
        e.preventDefault();
        menuArticleSendAjaxFormFilter();

    });


    function menuArticleCreateTagHTML(value) {
        let tagIndex = $('#inputsTagHiden').children().length;
        $(menuArticleTagPrototype.replace(/__name__/g, tagIndex)).val(value).hide().appendTo('#inputsTagHiden');
        let $tag = $('<a href="'+ tagIndex +'" class="badge badge-warning"></a>').click(function (e) {
            e.preventDefault();
            $(this).remove();
            $("[id=article_filter_tags_"+ $(this).attr('href') +"]").remove();
        });
        $tag.text(value).appendTo('#tagsList');
    }

    function menuArticleSendAjaxFormFilter(){
        let $form = $("form[name='article_filter']");
        console.log($form.serialize());
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize()})
            .done(function (data, textStatus, jqXDR) {
                menuArticleDisplayResult(data)
            });
    }

    function menuArticleDisplayResult(data) {
        data = JSON.parse(data);
        $('#table-body').empty();
        $.each(data, function (i, item) {
            menuArticleCreateTableLine(item);

        });
        $('.js-btn-suppr').each(function () {
            $(this).click(function () {
                $('#buttonValidDelete').attr('href', $(this).attr('href'));
                $('#buttonValidDelete').click(function (e) {
                    e.preventDefault();
                    eventSuppr('article');

                    menuArticleSendAjaxFormFilter();

                });

            });
        });
        $('.js-search-by-badge').each(function () {
            $(this).click(function () {
                $('#inputsTagHiden').children().remove();
                $('#tagsList').children().remove();
                menuArticleCreateTagHTML($(this).text());
                menuArticleSendAjaxFormFilter();


            })
        })
    }



    function menuArticleCreateTableLine(item) {
        let $t = $('#table-body');
        let tagHtml = '';
        let createdDate = new Date(item.created_at);
        createdDate = createdDate.getDate() + '/' + createdDate.getMonth() + '/' + createdDate.getFullYear();

        let editDate = '';
        if (item.last_edit != undefined)
        {
            editDate = new Date(item.last_edit);
            editDate = editDate.getDate() + '/' + editDate.getMonth() + '/' + editDate.getFullYear();
        }

        if (item.tags != undefined){
            item.tags.forEach(function (tag, index, array) {
                tagHtml += '<span class="badge badge-warning js-search-by-badge">'+ tag.tag_name +'</span>';
            });
        }

        $('<tr><th>'+ item.id +'</th>' +
            '<td>'+item.title+'</td>' +
            '<td>'+ (item.num_category != undefined ? item.num_category.libele : '')  +'</td>' +
            '<td>'+ (item.user != undefined ? item.user.username : '') +'</td>' +
            '<td>'+ createdDate +'</td>' +
            '<td>'+ editDate +'</td>' +
            '<td>'+ (item.description != undefined ? item.description : '') +'</td>'+
            '<td>'+ tagHtml +'</td>' +
            '<td><div class="btn-group"> <a href="/edit/'+ item.id +'" class="btn btn-secondary" role="link" >Editer</a><a href="/show/'+ item.id +'" class="btn btn-success">Voir</a><a href="/rmArticleA?id='+ item.id +'" class="btn btn-danger js-btn-suppr" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
            '</tr>').appendTo($t).hide().fadeIn(500);
    }

});



/**
 $('#article_dashboard_filter_tags').keypress(function (e) {
    if (e.key == 'Enter'){
        let $tag = $('<a href="#" class="badge badge-warning"></a>').click(function (e) {
            e.preventDefault();
            $(this).remove();
        });
        $tag.text($(this).val()).appendTo('#tagsList');
        $(this).val('');
    }
});

 let hrefArtcileToDelete = '';







 $('#validateArticleButton').click(function (e) {
    e.preventDefault();
    displayListArticle();
});

 export function displayListArticle() {

    let request = "";

    if ($('#article_dashboard_filter_title').val() != "")
    {
        request += '&title='+$('#article_dashboard_filter_title').val();
    }
    if ($('#article_dashboard_filter_numCategory').val().length > 0){

        $('#article_dashboard_filter_numCategory').val().forEach(function (item, index, array) {

            request += '&category'+ (Number(index) +1) +'='+item;
        })

    }

    if ($('#article_dashboard_filter_user').val() != ""){
        request += '&author='+$('#article_dashboard_filter_user').val();
    }
    if ($('#tagsList').children().length > 0){
        $('#tagsList').children().each(function (index) {
            request += '&tag'+ (Number(index) +1) +'='+$(this).text();
        });

    }

    if ($('#article_dashboard_filter_created_before').val() != ''){
        request += '&created_before=' + $('#article_dashboard_filter_created_before').val();
    }
    if ($('#article_dashboard_filter_created_after').val() != ''){
        request += '&created_after=' + $('#article_dashboard_filter_created_after').val();
    }
    console.log(request);

    $.getJSON('/get_info?t=article'+request)
        .done(function (data, textStatus, jqXDR) {
            $('#table-body').empty();
            $.each(data, function (i, item) {

                creatTableLine(item);
            })
            $('.js-btn-suppr').each(function () {
                $(this).click(function () {
                    $('#buttonValidDelete').attr('href', $(this).attr('href'));
                    $('#buttonValidDelete').click(function (e) {
                        e.preventDefault();
                        eventSuppr('article');

                    });

                });
            });
            $('.js-search-by-badge').each(function () {
                $(this).click(function () {
                    let $tag = $('<a href="#" class="badge badge-warning"></a>').click(function (e) {
                        e.preventDefault();
                        $(this).remove();
                    });
                    $tag.text($(this).text()).appendTo('#tagsList');


                    displayListArticle();

                })
            })
        })

}

 function creatTableLine(item) {

    let $t = $('#table-body');
    let tagHtml = ''
    let createdDate = new Date(item.created_at);
    createdDate = createdDate.getDate() + '/' + createdDate.getMonth() + '/' + createdDate.getFullYear();

    let editDate = '';
    if (item.last_edit != undefined)
    {
        editDate = new Date(item.last_edit);
        editDate = editDate.getDate() + '/' + editDate.getMonth() + '/' + editDate.getFullYear();
    }

    if (item.tags != undefined){
        item.tags.forEach(function (tag, index, array) {
            tagHtml += '<span class="badge badge-warning js-search-by-badge">'+ tag.tag_name +'</span>';
        });
    }

    $('<tr><th>'+ item.id +'</th>' +
        '<td>'+item.title+'</td>' +
        '<td>'+ (item.num_category != undefined ? item.num_category.libele : '')  +'</td>' +
        '<td>'+ (item.user != undefined ? item.user.username : '') +'</td>' +
        '<td>'+ createdDate +'</td>' +
        '<td>'+ editDate +'</td>' +
        '<td>'+ (item.description != undefined ? item.description : '') +'</td>'+
        '<td>'+ tagHtml +'</td>' +
        '<td><div class="btn-group"> <a href="/edit/'+ item.id +'" class="btn btn-secondary" role="link" >Editer</a><a href="/show/'+ item.id +'" class="btn btn-success">Voir</a><a href="/delete/'+ item.id +'" class="btn btn-danger js-btn-suppr" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
        '</tr>').appendTo($t).hide().fadeIn(500);
}
 */
