jQuery( document ).ready( function( $ ) {
	$('.soko-close-popup').click(function(){
        $('.soko-cookies-pop').hide(300);
        sessionStorage.setItem('dontLoad', 'true');
    });
    if (sessionStorage.getItem('dontLoad') == null){
        $(".soko-cookies-pop").show();
    }
});