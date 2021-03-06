require('../css/index.css')


$(document).ready(function () {
    let allowedCategories = $('#category-container').data('category-list').split(',');

    let cursor = 0;
    let nbColumnShowed = null;
    let posOffset = 0;
    let chargedCategories = [];
    let offset = 0;
    setNbColumnShowed();
    initChargedCategories();





  function setNbColumnShowed(){
    if (window.innerWidth <  960){
      nbColumnShowed = 1;
    }
    else{
      nbColumnShowed = 4;
    }

    $(window).resize(function () {
      if (window.innerWidth <  960){
        nbColumnShowed = 1;
        offset = cursor*100;
        $('.js-index-card').animate({
          right: offset+'%'
        }, 0);
      }
      else{
        nbColumnShowed = 4;


        if (offset%4 === 0){
          cursor = offset/100;
          offset = offset/4;
          $('.js-index-card').animate({
            right: offset+'%'
          }, 0);

        }else{
          cursor = cursor-(offset%4);
          offset -= 100*(offset%4);


          $('.js-index-card').animate({
            right: offset+'%'
          }, 0);
        }
      }
    });
  }

    function initChargedCategories(){
        for (let i=0; i<4; i++){
            chargedCategories.push(allowedCategories[i]);
        }
    }



    function addChargedCategory(categoryToAdd){
        if (allowedCategories[cursor+nbColumnShowed] !== undefined) {
            chargedCategories.push(categoryToAdd);
        }
    }

    function setCursorNextPosition(direction){

        if (direction === 'next'){
            if (nbColumnShowed == 4) {
                cursor += 4;
            }else{
                cursor++;
            }
        }else{
            if (nbColumnShowed == 4) {
                cursor -= 4;
            }else{
                cursor--;
            }
        }

    }



    $('#arrow-next').click(function (e) {

        if (allowedCategories[cursor+nbColumnShowed] !== undefined && chargedCategories[cursor+nbColumnShowed] === undefined) {

            let needdedCategory = [];
            for (let i=0; i<4 ; i++ ){
                if (allowedCategories[cursor+nbColumnShowed+i] !== undefined){
                    needdedCategory += allowedCategories[cursor+nbColumnShowed+i]+','
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

                for (let i=0; i<4;i++){
                    addChargedCategory(allowedCategories[cursor+nbColumnShowed+i]);
                }

                $('#category-container-card').append(data);
                offset+=100;
                $('.js-index-card').animate({
                    right: offset+'%'
                }, 300);

                $('#animation_loading_column').hide();
                $('#arrow-next').show();

                $('.js-index-card').hover(function (e) {
                    $(this).prev().children().first().removeClass('offset-img');
                    $(this).prev().children().first().addClass('offset-img-none');
                }, function (e) {
                    $(this).prev().children().first().removeClass('offset-img-none');
                    $(this).prev().children().first().addClass('offset-img');
                });
                setCursorNextPosition('next');


            })

        }else if (allowedCategories[cursor+nbColumnShowed] !== undefined && chargedCategories[cursor+nbColumnShowed] !== undefined){
            offset+=100;
            $('.js-index-card').animate({
                right: offset+'%'
            }, 300);


            setCursorNextPosition('next');


            console.log('');
            console.log('');
            console.log('');
            console.log('largeur colone : '+nbColumnShowed);
            console.log('categorie autorisée : '+allowedCategories);
            console.log('categories : '+chargedCategories);
            console.log('curseur : '+cursor);

        }



    });

    $('#arrow-previous').click(function (e) {
        if (allowedCategories[cursor-nbColumnShowed] !== undefined) {
            offset-=100;
            $('.js-index-card').animate({
                right: offset+'%'
            }, 300);

            setCursorNextPosition('prev');
        }
        console.log('');
        console.log('');
        console.log('');
        console.log('largeur colone : '+nbColumnShowed);
        console.log('offset : '+offset);
        console.log('categorie autorisée : '+allowedCategories);
        console.log('categories : '+chargedCategories);
        console.log('curseur : '+cursor);
    });

    $('.js-index-card-body').hover(function (e) {
        $(this).prev().children().first().removeClass('offset-img');
        $(this).prev().children().first().addClass('offset-img-none');
    }, function (e) {
        $(this).prev().children().first().removeClass('offset-img-none');
        $(this).prev().children().first().addClass('offset-img');
    });



    $('.js-index-card').each(function () {
        $(this).mouseenter(function (e) {



            let target = e.currentTarget.dataset.target;
            if (!  $('#'+target).hasClass('show'))
            {

                $('.js-index-card img').removeClass('slide-bottom-e');

                $(this).find('img').addClass('slide-bottom-e');

                $('.js-collapse-index').collapse('hide');
                $('#'+target).collapse('show');
            }

        });


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
});


