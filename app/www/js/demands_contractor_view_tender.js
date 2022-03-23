$(document).ready(function () {
    $(".tender_images").colorbox({rel:'tender_images'});
    $('div#update_bid_value').hide(); 
    $('div#submit_bid').hide(); 
    $('.amend_term_condition').hide(); 

    $('a.show_amend_term_condition').on("click", function(e) {
        e.preventDefault();

        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var elementId = myarr[1];
        
        $('div#form_amend_term_condition-' + elementId).show();
        $('p#existing_amendment-' + elementId).hide(); 
        $('div#control_amend_term_condition-' + elementId).hide();
        
        $(this).hide(); 

    });
    
    $('a.hide_form_amend_term_condition').on("click", function(e) {
        e.preventDefault();
        
        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var elementId = myarr[1];
        
        $('div#form_amend_term_condition-' + elementId).hide();
        $('p#existing_amendment-' + elementId).show(); 
        $('div#control_amend_term_condition-' + elementId).show();
        
    });
    

    $('.confirmation').on('click', function () {
        return confirm('Are you sure?');
    });    
        
    $('a#show_update_bid_value').on("click", function(e) {
        e.preventDefault();
        $('div#update_bid_value').show(); 
        $('p#bid_value').hide(); 
        $('div#control_update_bid_value').hide();

    });
    
    $('a#hide_form_update_bid_value').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_bid_value').hide(); 
        $('p#bid_value').show(); 
        $('div#control_update_bid_value').show();

    });
    
    $('div#update_bid_comments').hide(); 
    
    $('a#show_update_bid_comments').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_bid_comments').show(); 
        $('p#bid_comments').hide(); 
        $('div#control_update_bid_comments').hide();

    });
    
    $('a#hide_form_update_bid_comments').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_bid_comments').hide(); 
        $('p#bid_comments').show(); 
        $('div#control_update_bid_comments').show();

    });

    

});
