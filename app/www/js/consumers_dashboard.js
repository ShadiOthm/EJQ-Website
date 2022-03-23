$(document).ready(function () {
    $('div#update_address').hide(); 
    $("input.phone").mask("999-999-9999");
    
    $('a#show_update_address').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_address').show(); 
        $('p#address').hide(); 
        $('div#control_update_address').hide();

    });
    
    $('a#hide_form_update_address').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_address').hide(); 
        $('p#address').show(); 
        $('div#control_update_address').show();

    });


    $('div#update_phone').hide(); 
    
    $('a#show_update_phone').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_phone').show(); 
        $('p#phone').hide(); 
        $('div#control_update_phone').hide();

    });
    
    $('a#hide_form_update_phone').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_phone').hide(); 
        $('p#phone').show(); 
        $('div#control_update_phone').show();

    });

});
