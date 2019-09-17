require('../css/index.css')


let allowedCategories = $('#category-container').data('category-list').split(',');

let chargedCategory = [allowedCategories[0], allowedCategories[1], allowedCategories[2]];
let displayedCategory = [allowedCategories[0], allowedCategories[1], allowedCategories[2]];


let selected = 0;
let selectedWidth;
let posOffset = 0;

$(window).resize(
    ResponsiveBootstrapToolkit.changed(function() {
        if (ResponsiveBootstrapToolkit.is('<lg')){
            selectedWidth = 1;
        }
        else{
            selectedWidth = 3
        }
        console.log(selectedWidth);
    })
);


function setColumnId(){
    $('#category-container-card').children().each(function (loop, element) {
        $(element).attr('id', 'col-'+Number(loop+1));
    });
}


$('#arrow-next').click(function (e) {


    if (allowedCategories[selected+selectedWidth] !== undefined && chargedCategory[selected+selectedWidth] === undefined) {

        let needdedCategory = [];
        for (let i=0; i<3 ; i++ ){
            if (allowedCategories[selected+selectedWidth+i] !== undefined){
                needdedCategory += allowedCategories[selected+selectedWidth+i]+','
            }
        }

        $.ajax({
            url: '/index/getCategoryCards',
            method: 'GET',
            data: 'categories='+needdedCategory,
            beforeSend: function () {

                $('#arrow-next').hide();
                $('#animation_loading_column').show()
            }
        }).done(function (data) {

            chargedCategory.push(allowedCategories[selected+selectedWidth]);
            chargedCategory.push(allowedCategories[selected+1+selectedWidth]);
            chargedCategory.push(allowedCategories[selected+2+selectedWidth]);
            
            displayedCategory = [allowedCategories[selected+selectedWidth], allowedCategories[selected+1+selectedWidth], allowedCategories[selected+2+selectedWidth]];

            posOffset += 100;

            $('#category-container-card').append(data);

            $('.js-index-card').animate({
                right: '100%'
            }, 300, function () {
                chargedCategory.forEach(function (element) {
                    console.log(element);
                    if (!displayedCategory.includes(element)){
                        $('#col-'+element).attr("style", "display: none !important");
                    }
                    $('.js-index-card').animate({
                        right: '0%'
                    }, 0);
                })
            });


            $('#animation_loading_column').hide();
            $('#arrow-next').show();

            

            if (ResponsiveBootstrapToolkit.is('<lg')){
                selected++
            }else{
                selected += 3;
            }

            console.log(allowedCategories);
            console.log(chargedCategory);
            console.log(selected);
        })

    }else if (allowedCategories[selected+selectedWidth] !== undefined && chargedCategory[selected+selectedWidth] !== undefined){
        if (ResponsiveBootstrapToolkit.is('<lg')){
            displayedCategory = [allowedCategories[selected+selectedWidth]];
        }else{
            displayedCategory = [allowedCategories[selected+selectedWidth], allowedCategories[selected+1+selectedWidth], allowedCategories[selected+2+selectedWidth]];
        }


        $('.js-index-card').animate({
            right: '100%'
        }, 300, function () {
            chargedCategory.forEach(function (element) {

                if (!displayedCategory.includes(element)){
                    $('#col-'+element).attr("style", "display: none !important");
                }
                $('.js-index-card').animate({
                    right: '0%'
                }, 0);
            })
        });
        if (ResponsiveBootstrapToolkit.is('<lg')){
            selected++
        }else{
            selected += 3;
        }
        console.log(allowedCategories);
        console.log(chargedCategory);
        console.log(selected);
        console.log(displayedCategory);
    }



});

$('#arrow-previous').click(function (e) {
    if (allowedCategories[selected-selectedWidth] !== undefined) {
        posOffset -= 100;
        $('.js-index-card').animate({
            right: posOffset+'%'
        }, 300, function () {
            for (let i=selected+selectedWidth;i<=selected+selectedWidth+3;i++){
                $('#col-'+i).attr("style", "display: flex !important");
            }
            $('.js-index-card').animate({
                right: posOffset+'%'
            }, 0);
            posOffset=0;
        });
        if (ResponsiveBootstrapToolkit.is('<lg')){
            selected--
        }else{
            selected -= 3;
        }
    }
    console.log(allowedCategories);
    console.log(chargedCategory);
    console.log(selected);
});

$('.js-index-card').hover(function (e) {
    $(this).prev().children().first().removeClass('offset-img');
    $(this).prev().children().first().addClass('offset-img-none');
}, function (e) {
    $(this).prev().children().first().removeClass('offset-img-none');
    $(this).prev().children().first().addClass('offset-img');
});






const ratio = .2;
const options = {
    root: null,
    rootMargin: '0px',
    threshold: ratio,
};

const handleIntersect = function (entries, observer){
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio){
            entry.target.classList.add('revealX-visible');
            observer.unobserve(entry.target)
        }
    });
};

let observer = new IntersectionObserver(handleIntersect, options);

document.querySelectorAll('.revealX').forEach(function (r) {
    observer.observe(r);
});

