
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

    /* Création de la pagination */
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