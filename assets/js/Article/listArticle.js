import { eventSuppr } from '../AdminDashboard/AdminDashboard.js';
import { displayPagination } from '../globalFunctions';
import { $_GET } from '../globalFunctions';


$(document).ready(function () {
    let urlParam = $_GET();


    let menuArticleTagPrototype = $('#article_filter_tags').attr('data-prototype');
    menuArticleTagPrototype = $(menuArticleTagPrototype).find('input').prop('outerHTML');

    let menuArticleInputToAddTag = $('<div class="form-group"><label for="genericTagInput">Tags</label><input type="text" id="genericTagInput" placeholder="Ajouter un tag à rechercher" class="form-control"></div><div id="inputsTagHiden"></div>')
    $('#article_filter_tags').parent().replaceWith(menuArticleInputToAddTag);


    $('#selecter-article').selectpicker().change(function () {
        $('#article_filter_nbResult').val('');

        $('#article_filter_nbResult').val($("#selecter-article option:selected").text());
        menuArticleSendAjaxFormFilter();
    });

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

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $('#card-parent').fadeOut(400, function () {
                    $('#spinnerLoadingGeneralSearch-list').addClass('d-block');
                    $('#spinnerLoadingGeneralSearch-list').show();
                })

            }

        })
            .done(function (data) {
                setTimeout(function () {
                    $('#spinnerLoadingGeneralSearch-list').removeClass('d-block');
                    $('#spinnerLoadingGeneralSearch-list').hide();
                    $('#card-parent').empty();
                    $('#card-parent').show();
                    menuArticleDisplayResult(data)
                },420);

            });
    }

    function menuArticleDisplayResult(data) {
        let pageActive = $('#article_filter_pageSelected').val();
        let result = JSON.parse(data)

        console.log(result);

        $.ajax({
            url: 'article/getCard',
            method: 'POST'})
            .done(function (data, textStatus, jqXDR) {

                let regex = /#id#/gi;

                $.each(result.result, function (i, item) {
                    let cardTemplate = data.replace(regex, item.id);
                    $(cardTemplate).appendTo('#card-parent');

                    customizeCard(item, result.canEdit, result.canDelete);

                });


            });



        displayPagination(result.nbPage, pageActive, function (e) {
            e.preventDefault();
            $('#article_filter_pageSelected').val($(this).children().text());
            menuArticleSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#article_filter_pageSelected').val(Number(pageActive)-1);
            menuArticleSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#article_filter_pageSelected').val(Number(pageActive)+1);
            menuArticleSendAjaxFormFilter();
        }, 'article',$('#article_filter_pageSelected'))
    }



    function customizeCard(article, canEdit, canDelete) {

        $('#card-title-' + article.id).text(article.title);
        $('#card-img-' + article.id).attr('src', '/images/' + article.image.file_name);
        $('#card-description-' + article.id).text(article.description);
        $('#btn-show-' + article.id).attr('href', '/show/' + article.id);
        //$('#btn-info-' + article.id).attr('data-content', '<img src=images/'+ article.image.file_name +' class=\'card-img-top\'> <hr>'+ article.description);

        $('#card-category-' + article.id).text(article.num_category.libele);
        if (article.user != undefined) {
            $('#card-user-' + article.id).text(article.user.username);
        }
        let createdDate = new Date(article.created_at);
        createdDate = createdDate.getDate() + '/' + createdDate.getMonth() + '/' + createdDate.getFullYear();
        $('#card-createdAt-' + article.id).text(createdDate);

        $('#collapseTags-' + article.id).empty();
        if (article.tags.length > 0) {
            for (let tag of article.tags) {

                let span = $('<span></span>').text(tag.tag_name).addClass("badge badge-success-custom mr-1");
                $('#collapseTags-' + article.id).append(span);
            }
        }
        else{
            $('#card-TagsBtn-'+article.id).attr('disabled', 'disabled');
        }


        let popoverContent = "";
        if (canEdit[article.id]) {
            popoverContent += '<a  role=\"button\" href=\"/article/edit/' + article.id + '\" class=\"btn btn-secondary btn-sm w-100\">Editer</a><br>'
        }

        if (canDelete[article.id]) {
            popoverContent += '<a  role="button" data-toggle="modal" data-target="#modalValiddelete" href="/rmArticleA/?id=' + article.id + '" class="btn btn-danger btn-sm w-100 js-btn-suppr" >Supprimer</a><br>'
        }

        $('#card-popover-' + article.id).popover({
            html: true,
            content: popoverContent,
            trigger: 'focus',
        });

        $('#card-popover-' + article.id).on('inserted.bs.popover', function () {

            $("a[class*='js-btn-suppr']").click(function (e) {
                e.preventDefault();
                $('#modalValiddelete').modal({
                    keyboard: false
                });
                $('#buttonValidDelete').attr('href', $(this).attr('href'));
                $('#buttonValidDelete').click(function (e) {
                    e.preventDefault();
                    eventSuppr('article');

                    menuArticleSendAjaxFormFilter();

                });

            });

        });

        //$('#card-' + article.id).removeClass('revealX');
        //$('#card-' + article.id).addClass('revealX-visible');
    }
    if (Object.entries(urlParam).length > 0 && urlParam.constructor === Object) {
        if (urlParam['content']){
            $('#article_filter_content').val(urlParam['content']);
            menuArticleSendAjaxFormFilter();
        }
        if (urlParam['tag']){
            menuArticleCreateTagHTML(urlParam['tag'].toUpperCase());
            menuArticleSendAjaxFormFilter();
        }

    }else{
        menuArticleSendAjaxFormFilter();
    }

});


