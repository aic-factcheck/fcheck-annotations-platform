$("#toggler").click(function () {
    for (let i = 0; i < 30; i++) {
        setTimeout(function(){
            jQuery(window).trigger('resize').trigger('scroll');
        }, 10*i);
    }
});
