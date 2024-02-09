require([
    'jquery'
], function($) {

    $(document).ready(function () {
        let footerScrollTop = 0;

        $(window).on('scroll', function() {
            let footerScroll = $(this).scrollTop();
            if (footerScroll < footerScrollTop) {
                $('.footer-categories').addClass('fixed');
            } else {
                $('.footer-categories').removeClass('fixed');
            }

            if (footerScroll < 1) {
                $('.footer-categories').removeClass('fixed');
            }

            footerScrollTop = footerScroll;
        });
    });
});
