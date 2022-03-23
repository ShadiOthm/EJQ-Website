$(document).ready(function () {

    $('#bids').on("click", "a.add_to_shortlist", function(e) {
        e.preventDefault(e);
        add_to_shortlist(this);
    });
    
    $('#bids').on("click", "a.remove_from_shortlist", function(e) {
        e.preventDefault();
        remove_from_shortlist(this);
    });
    
    $('#bids').on("click", "a.back_to_bids_list", function(e) {
        e.preventDefault();
        back_to_bids_list(this);
    });
    
    
    function add_to_shortlist(e) {
        if (e) {
            var idClick = $(e).attr('id');
            var myarr = idClick.split("-");
            var elementId = myarr[1];
            clicked_element = $(e).parent();
            var request = $.ajax({
                url: '/bids/add_to_shortlist',
                type: "POST",
                // dataType: "json",
                data: {
                    id: elementId,
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

            return false;
        }
    }
    
    function remove_from_shortlist(e) {
        if(e) {

            var idClick = $(e).attr('id');
            var myarr = idClick.split("-");
            var elementId = myarr[1];
            clicked_element = $(e).parent();

            var request = $.ajax({
                url: '/bids/remove_from_shortlist',
                type: "POST",
                // dataType: "json",
                data: {
                    id: elementId,
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

            return false;

            
        }
    }
        
    function back_to_bids_list(e) {
        if(e) {

            var idClick = $(e).attr('id');
            var myarr = idClick.split("-");
            var elementId = myarr[1];
            var request = $.ajax({
                url: '/tenders/see_all_bids',
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
                $("#bids").html(data);
            });
            request.fail(function (jqXHR, textStatus, errorThrown) {
            });

            return false;

            
        }
        
    }






    $('#bids').on("click", "a#hide_form_choose_bid", function(e) {
        e.preventDefault(e);
        hide_form_choose_bid(this);
    });
    


    function hide_form_choose_bid(e) {
        if (e) {
            $('div#choose_bid').hide(); 
            
        }
        
    }
        
    
    $('#bids').on("click", "a#show_choose_bid", function(e) {
        e.preventDefault(e);
        show_choose_bid_div(this);
    });
    


    function show_choose_bid_div(e) {
        if (e) {
            $('div#choose_bid').show(); 
            
        }
        
    }
        

});
