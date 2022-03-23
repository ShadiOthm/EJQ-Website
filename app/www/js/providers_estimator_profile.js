$(document).ready(function () {
    $('div#provider_service_types').hide(); 

    $('.accept_demand').on("click", function(e) {
        e.preventDefault();

        id = $(this).attr('id');
        clicked_element = $(this).parent();

        var request = $.ajax({
            url: '/demands/accept/' + id,
            type: "POST",
            // dataType: "json",
            data: {},
            cache: false
        });
        request.always(function (a) {
            
        });
        request.done(function (data) {
            clicked_element.html(data);
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            
        });
    });
});

$(document).ready(function () {
    $('.inform_demand_supply').on("click", function(e) {
        e.preventDefault();

        id = $(this).attr('id');
        clicked_element = $(this).parent();
        
        var request = $.ajax({
            url: '/demands/supply/' + id,
            type: "POST",
            // dataType: "json",
            data: {},
            cache: false
        });
        request.always(function (a) {
            
        });
        request.done(function (data) {
            clicked_element.html(data);
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            
        });
    });

    $('a#show_update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('div#provider_service_types').show(); 
        $(this).hide();

    });
    
    $('a#hide_form_update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('div#provider_service_types').hide(); 
        $('a#show_update_service_types').show();

    });
    


});
