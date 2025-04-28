(function ($) {
    wp.customize('degikart_logo', function (value) {
        value.bind(function (newval) {
            $('.site-logo').attr('src', newval);
        });
    });

    wp.customize('degikart_logo_width', function (value) {
        value.bind(function (newval) {
            $('.site-logo').css('width', newval + 'px');
        });
    });
})(jQuery);



document.addEventListener("DOMContentLoaded", function() {
    var header = document.getElementById("mobile-nav");
    var body = document.body;
    
    function adjustPadding() {
        var headerHeight = header.offsetHeight;
        body.style.paddingTop = headerHeight + "eex";
    }

    adjustPadding();
    window.addEventListener("resize", adjustPadding);
});


























 