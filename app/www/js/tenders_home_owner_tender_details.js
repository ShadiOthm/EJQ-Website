$(document).ready(function () {
    $('.choose_bid').on("click", function(e) {
        e.preventDefault();

        id = $(this).attr('id');
        bidId = $(this).attr('bid_id');
        tenderId = $(this).attr('tender_id');
        clicked_element = $(this).parent();

        var request = $.ajax({
            url: '/tenders/choose_bid?id=' + id + '&bid_id=' + bidId + '&tender_id=' + tenderId,
            type: "GET",
            // dataType: "json",
            data: {
//                id: id,
//                bid_id: bidId,
            },
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
    
    $(document).on('click', 'a.see_shortlist', function(e){
        e.preventDefault();

        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var elementId = myarr[1];
        alert("YEP");
        clicked_element = $(this).parent();
        var request = $.ajax({
            url: '/tenders/see_shortlist',
            type: "POST",
            // dataType: "json",
            data: {
                    Tender: {id: elementId }
            },
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
    
    $(document).on('click', 'a.see_all_bids', function(e){
        e.preventDefault();

        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var termId = myarr[1];
        clicked_element = $(this).parent();

        var request = $.ajax({
            url: '/tenders/see_all_bids',
            type: "POST",
            // dataType: "json",
            data: {
                id: termId,
            },
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

    $('button#update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('form#update_service_types').show(); 
        $(this).hide();

    });
    
    $('a#hide_form_update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('form#update_service_types').hide(); 
        $('button#update_service_types').show();

    });
    
});
