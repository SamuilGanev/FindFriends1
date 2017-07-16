/**
 * Created by 112 on 12.7.2017 г..
 */
$(document).ready(function(){
    $('.createpost').hide();
    $('#close').click(function(){
        $('.createpost').slideUp();
    });
    $('#newpost').click(function(){
        $('.createpost').slideDown();
    });
});