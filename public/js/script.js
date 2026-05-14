$(window).on("load", function() {
    $(".button-up").mPageScroll2id();

    $("#create__card").mPageScroll2id();

    $(".headsearch__navul a").mPageScroll2id({
        offset : 50
    });
    $(".side-menu a").mPageScroll2id({
        offset : 80
    });

    // $("a[href*='#']").mPageScroll2id({
    //     offset: 220
    // });
    $(window).scroll(function() {
        if ($(this).scrollTop() > 500) {
            $('.button-up').css('visibility', 'visible')
                           .css('bottom', '60px');

        } else {
            $('.button-up').css('visibility', 'hidden')
                           .css('bottom', '-70px');
        }
    });

    var menu = $('.side-menu');
    var burger = $('.hdr__sidebar');
    // var exit = $('.head-nav-close');
    var links = $(".side-menu").find("a")

    burger.on('click', function(event) {
        // menu.removeClass('burger-settings');
        // menu.css({'display':'flex'});
        menu.toggleClass('side-menu-active');
        event.preventDefault();
    });

    $(document).mouseup(function (e){
        if (!menu.is(e.target) && menu.has(e.target).length === 0) {
            // menu.css({'display':'flex'});
            menu.removeClass('side-menu-active');
            event.preventDefault();
        }
    });


    links.click(function() {
        menu.removeClass('side-menu-active');
    });
});