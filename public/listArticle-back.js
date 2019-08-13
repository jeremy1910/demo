

var searchButton = document.getElementById("searchButton");
var searchAttr = document.getElementById("recherche")
var categoryAttr = document.getElementById("category")
var searchButtonloading = document.getElementById("searchButtonLoading")
var listeOfPages = document.getElementById("pagination")
var supprButtons = document.getElementsByClassName('js-btn-suppr')

var buttonValidDelete = document.getElementById('buttonValidDelete')

var hrefArtcileToDelete = ''
var lastFilter = ''
let url = ''

for(pageNumber of listeOfPages.children)
{
    pageNumber.firstElementChild.addEventListener('click', eventClickPage)
}

for ( supprButton of supprButtons)
{
    supprButton.addEventListener('click', eventLauncheModal)
}

searchButton.addEventListener("click", eventClickSearchButton)

buttonValidDelete.addEventListener("click", eventSuppr)

function eventClickSearchButton(e) {
    let xhr = new XMLHttpRequest()
    lastFilter = ''

    e.preventDefault();

    if (searchAttr.value != '')
    {
        lastFilter = "recherche="+searchAttr.value+'&'

    }
    lastFilter += "category="+categoryAttr.options[categoryAttr.selectedIndex].value+'&'
    url += lastFilter


    xhr.onreadystatechange = function() {

        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
            displayArticles(xhr)
        } else {
            displayloading()
        }

    }

    xhr.open("GET", "/getResult?" + url, true);
    xhr.send(null);


}

function eventClickPage(e) {

    e.preventDefault();

    let xhr = new XMLHttpRequest()


    url += lastFilter

    xhr.onreadystatechange = function() {

        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
            displayArticles(xhr)
        } else {
            displayloading()
        }

    }

    xhr.open("GET", this.href, true);
    xhr.send(null);

}
var result;
function displayArticles(xhr){
    result = JSON.parse(xhr.responseText)


    let i = 0;
    function myLoop () {
        setTimeout(function () {


            $("#card-+i").css("display", "none");
            $('#card-'+i).fadeOut(10).fadeIn(800);



            if (result.articles[i]) {
                document.getElementById('card-'+i).style.display = "block";
                document.getElementById('card-title-'+i).innerHTML = result.articles[i].title;
                document.getElementById('card-category-'+i).innerHTML = result.articles[i].numCategory;

                document.getElementById('btn-show-'+i).href = '/show/' + result.articles[i].id;
                if (result.articles[i].canEdit) {
                    document.getElementById('btn-edit-' + i).href = '/edit/' + result.articles[i].id;
                }
                if (result.articles[i].canDelete) {
                    document.getElementById('btn-delete-' + i).href = '/delete/' + result.articles[i].id;
                }
                document.getElementById('btn-info-'+i).setAttribute('data-original-title', result.articles[i].title);
                document.getElementById('btn-info-'+i).setAttribute('data-content', "<img src=\'/images/" + result.articles[i].image + '\' class=\'card-img-top\'> <hr> '+ result.articles[i].description );

                $('#collapseTags-'+i).empty();
                if (result.articles[i].tags)
                {
                    console.log('toto');

                    for (let tag of result.articles[i].tags)
                    {
                        console.log(tag);
                        let span = $('<span></span>').text(tag).addClass("badge badge-dark");
                        $('#collapseTags-'+i).append(span);
                    }
                }


            }
            else
            {
                document.getElementById('card-'+i).style.display = "none"
            }

            i++;
            if (i < 12) {
                myLoop();
            }
        }, 50)
    }

    myLoop();

/*

    for(let i=0; i<12; i++)
    {
        //document.getElementById('card-'+i).classList.remove("js-card-translateEffect");
        //document.getElementById('card-'+i).offsetWidth;
        //document.getElementById('card-'+i).classList.add("js-card-translateEffect", false);

            $("#card-+i").css("display", "none");
            $('#card-'+i).fadeOut(10).fadeIn(600);



        if (result.articles[i]) {
            document.getElementById('card-'+i).style.display = "block";
            document.getElementById('card-title-'+i).innerHTML = result.articles[i].title;
            document.getElementById('card-category-'+i).innerHTML = result.articles[i].numCategory;

            document.getElementById('btn-show-'+i).href = '/show/' + result.articles[i].id;
            if (result.articles[i].canEdit) {
                document.getElementById('btn-edit-' + i).href = '/edit/' + result.articles[i].id;
            }
            if (result.articles[i].canDelete) {
                document.getElementById('btn-delete-' + i).href = '/delete/' + result.articles[i].id;
            }
            document.getElementById('btn-info-'+i).setAttribute('data-original-title', result.articles[i].title);
            document.getElementById('btn-info-'+i).setAttribute('data-content', "<img src=\'/images/" + result.articles[i].image + '\' class=\'card-img-top\'> <hr> '+ result.articles[i].description );



        }
        else
        {
            document.getElementById('card-'+i).style.display = "none"
        }




    }
*/

    let pagination = document.getElementById("pagination")

    while(pagination.firstElementChild)
    {
        pagination.firstElementChild.remove()
    }

    let liPrev = document.createElement('li')
    let aPrev = document.createElement('a')
    liPrev.classList.add('page-item')
    aPrev.classList.add('page-link')

    aPrev.innerHTML = 'Previous'

    if (result.pageActive == 1)
    {
        aPrev.disabled = true
    }
    else
    {
        aPrev.href = '/getResult?page=' + (result.pageActive -1) + '&' + url
        aPrev.addEventListener('click', eventClickPage)
    }
    liPrev.appendChild(aPrev)
    pagination.appendChild(liPrev)

    for (let i=1; i<=result.numberPages; i++)
    {

        let li = document.createElement('li')
        li.classList.add('page-item')

        let a = document.createElement('a')
        a.classList.add('page-link')
        a.href = '/getResult?page=' + i + '&' + url
        a.innerHTML = i
        if (result.pageActive == i)
        {
            li.classList.add('active')
        }
        a.addEventListener('click', eventClickPage)
        li.appendChild(a)
        document.getElementById('pagination').appendChild(li)


    }

    let liNext = document.createElement('li')
    let aNext = document.createElement('a')
    liNext.classList.add('page-item')
    aNext.classList.add('page-link')

    aNext.innerHTML = 'Next'

    if (result.pageActive == result.numberPages)
    {
        aNext.disabled = true
    }
    else
    {
        aNext.href = '/getResult?page=' + (Number(result.pageActive) +1) + '&' + url
        aNext.addEventListener('click', eventClickPage)
    }
    liNext.appendChild(aNext)
    pagination.appendChild(liNext)


    searchButtonloading.style.display = "none"
    searchButton.style.display = "inline-block"

    url = ''

}

function eventLauncheModal(e) {
    hrefArtcileToDelete = this.href
}

function eventSuppr(e) {
    e.preventDefault();


    let xhr = new XMLHttpRequest()

    xhr.onreadystatechange = function() {

        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
            displayArticles(xhr)
        } else {

            displayloading()
        }

    }


    xhr.open("GET", hrefArtcileToDelete + url, true);
    xhr.send(null);

}

function displayloading () {
    searchButton.style.display = "none"
    searchButtonloading.style.display = "inline-block"
}

