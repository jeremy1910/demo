import { eventSuppr } from '../AdminDashboard/AdminDashboard.js';
import { displayPagination } from '../globalFunctions';

$(document).ready(function () {

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
            data: $form.serialize()})
            .done(function (data, textStatus, jqXDR) {
                menuArticleDisplayResult(data)
            });
    }

    function menuArticleDisplayResult(data) {
        let pageActive = $('#article_filter_pageSelected').val();
        let result = JSON.parse(data)

        $.ajax({
            url: 'article/getCard',
            method: 'POST'})
            .done(function (data, textStatus, jqXDR) {

                let regex = /#id#/gi;
                $('#card-parent').empty();
                $.each(result.result, function (i, item) {
                    let cardTemplate = data.replace(regex, item.id);
                    $(cardTemplate).appendTo('#card-parent');
                    //$('#card-' + item.id).addClass('revealX-visible');
                    customizeCard(item, result.canEdit, result.canDelete);

                });


            });


        /*
        $('.js-search-by-badge').each(function () {
            $(this).click(function () {
                $('#inputsTagHiden').children().remove();
                $('#tagsList').children().remove();
                menuArticleCreateTagHTML($(this).text());
                menuArticleSendAjaxFormFilter();


            })
        });
        $('.js-menuArticle-badge-user').each(function () {
            $(this).click(function () {
                $('#article_filter_user').val($(this).text());
                menuArticleSendAjaxFormFilter();
            })
        });

        $('.js-menuArticle-badge-category').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                $('#article_filter_numCategory').selectpicker('val', $(this).attr('href'));
                menuArticleSendAjaxFormFilter();
            })
        });
        */

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

                let span = $('<span></span>').text(tag.tag_name).addClass("badge badge-dark");
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
        console.log(popoverContent);
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

    menuArticleSendAjaxFormFilter();
});



/**
 let url = '';
 let lastFilter = '';
 let hrefArtcileToDelete = '';

 $('#searchButton').on('click', eventClickSearchButton);
 $("#pagination a").each(function () {
    $(this).click(eventClickPage)
});

 $('.js-btn-suppr').each(function () {
   $(this).click(function () {
       hrefArtcileToDelete = $(this).attr('href');
   });
});

 $('#buttonValidDelete').click(eventSuppr);




 function eventClickSearchButton(e) {
    e.preventDefault();

    if ($('#recherche').val() != '')
    {
        lastFilter = "recherche="+$('#recherche').val()+'&';

    }
    lastFilter += "category="+$('#category').val()+'&';
    url += lastFilter;
    console.log(url);
    displayloading();
    $.get("/getResult?" + url)
        .done(function(data, textStatus, jqXDR) {

            displayArticles(data);
            displayPagination(data);
        })
        .always(function () {
            $('#searchButtonLoading').css({'display': 'none'});
            $('#searchButton').css({'display': 'inline-block'});
        });

}

 function displayArticles(data){

    let i = 0;

    console.log(data);

        function loopEachCard() {
            setTimeout(function () {

                if (data.articles[i]) {
                    $('#card-'+i).fadeOut(10);

                    $('#card-title-' + i).text(data.articles[i].title);
                    $('#card-img-' + i).attr('src', '/images/' + data.articles[i].image);
                    $('#card-description-' + i).text(data.articles[i].description);
                    $('#btn-show-' + i).attr('href', '/show/' + data.articles[i].id);
                    $('#btn-edit-' + i).remove();
                    if (data.articles[i].canEdit) {
                        $('#btn-edit-' + i).attr('href', '/edit/' + data.articles[i].id);
                    }
                    $('#btn-delete-' + i).remove();
                    if (data.articles[i].canDelete) {
                        $('#btn-delete-' + i).attr('href', '/delete/' + data.articles[i].id);
                    }
                    $('#card-category-' + i).text(data.articles[i].numCategory);

                    $('#collapseTags-' + i).empty();
                    if (data.articles[i].tags) {
                        for (let tag of data.articles[i].tags) {

                            let span = $('<span></span>').text(tag).addClass("badge badge-dark");
                            $('#collapseTags-' + i).append(span);
                        }
                    }
                    $('#card-'+i).fadeIn(500);
                }
                else {
                    $('#card-'+i).fadeOut(10);
                }
                i++;
                if (i < $("#card-parent > div").length){
                    loopEachCard();
                }

            }, 50);

        }

    loopEachCard();

}

 function  displayPagination(data)
 {
     $('#pagination').empty(); // Supression de la pagination existante


     let $liPrev = $('<li></li>').addClass('page-item');
     let $aPrev = $('<a>').addClass('page-link').text('Précédent');
     if (data.pageActive == 1)
     {
         $($aPrev).disabled = true
     }
     else
     {
         $($aPrev).attr('href', '/getResult?page=' + (data.pageActive -1) + '&' + url);
         $($aPrev).click(eventClickPage);
     }
     $liPrev.append($aPrev);
     $('#pagination').append($liPrev);



     /* Création de la pagination
     for (let i=1; i<=data.numberPages; i++)
     {
         let li = $('<li></li>').addClass('page-item');
         let a =  $('<a>').addClass('page-link').attr('href', '/getResult?page=' + i + '&' + url).text(i);
         $(a).click(eventClickPage);
         if (data.pageActive == i)
         {
             li.addClass('active');
         }
         li.append(a);
         $('#pagination').append(li);
     }

     let $liNext = $('<li></li>').addClass('page-item');
     let $aNext = $('<a>').addClass('page-link').text('Suivant');
     if (data.pageActive == data.numberPages)
     {
         $($aNext).disabled = true
     }
     else
     {
         $($aNext).attr('href', '/getResult?page=' + (Number(data.pageActive) +1) + '&' + url);
         $($aNext).click(eventClickPage);
     }
     $liNext.append($aNext);
     $('#pagination').append($liNext);
 }

 function eventClickPage(e) {
    e.preventDefault();

    url += lastFilter
    $.get(this.href)
        .done(function (data, textStatus, jqXDR) {
            displayArticles(data);
            displayPagination(data);
        });
}

 function eventSuppr(e) {
    e.preventDefault();
    console.log("suppreion de : " + hrefArtcileToDelete);
   $.get(hrefArtcileToDelete + url)
       .done(function (data, textStatus, jqXDR) {
           displayArticles(data);
           displayPagination(data);

       });

}

 function displayloading () {
    console.log('OK');
    $('#searchButton').css({'display': 'none'});
    $('#searchButtonLoading').css({'display': 'inline-block'});
}
 */