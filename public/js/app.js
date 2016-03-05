/**
 * Created by dave on 3/4/16.
 */

// dismiss success alerts with a click
$('.alert-dismissible').click( function(){
    $(this).slideUp();
});
setTimeout(function() {
    $('.alert-dismissible').slideUp('fast');
}, 2000); // <-- time in milliseconds

