// side-bar click menu
$(document).ready(function () {
    $('header .main-nav .click-menu').click(function () {
        $('header .main-nav nav').addClass('show')
        $('header .main-nav .cancel-menu').addClass('show')
        $('body').css({ 'position': 'fixed', 'width': '100%' });
    });

    $('header .main-nav .cancel-menu').click(function () {
        $('header .main-nav nav').removeClass('show')
        $('header .main-nav .cancel-menu').removeClass('show')
        $('body').css({ 'position': 'unset', 'width': 'auto' });
    });
});

// header fix
$(window).scroll(function(){
    if ($(window).scrollTop () > 50) {
        $('header').addClass('show');
    }
    else {
        $('header').removeClass('show');
    }
});
