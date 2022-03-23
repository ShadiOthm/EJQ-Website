$(document).ready(function () {
    
    $(".tender_images").colorbox({rel:'tender_images'});
    $('div#update_tender_description').hide(); 
    $('div#add_condition_to_tender').hide(); 
    $('.update_tender_term_condition').hide(); 
    $('div#add_image').hide(); 
    $('.update_tender_image_caption').hide(); 
    
    $('a#show_update_tender_description').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_tender_description').show(); 
        $('p#tender_description').hide(); 
        $('div#control_update_tender_description').hide();

    });
    
    $('a#hide_form_update_tender_description').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_tender_description').hide(); 
        $('p#tender_description').show(); 
        $('div#control_update_tender_description').show();

    });

    $('a#update_tender_title').on("click", function(e) {
        e.preventDefault();
        
        $('form#update_tender_title').show(); 
        $('p#tender_title').hide(); 
        $(this).hide();

    });
    
    $('a#hide_form_update_tender_title').on("click", function(e) {
        e.preventDefault();
        
        $('form#update_tender_title').hide(); 
        $('p#tender_title').show(); 
        $('a#update_tender_title').show();

    });

    $('a#show_add_condition_to_tender').on("click", function(e) {
        e.preventDefault();
        
        $('div#add_condition_to_tender').show(); 
        $('div#control_add_condition_to_tender').hide();

    });
    
    $('a#hide_form_add_condition_to_tender').on("click", function(e) {
        e.preventDefault();
        
        $('div#add_condition_to_tender').hide(); 
        $('div#control_add_condition_to_tender').show();

    });

    $('a#show_add_image').on("click", function(e) {
        e.preventDefault();
        
        $('div#add_image').show(); 
        $('div#control_add_image').hide();

    });
    
    $('a#hide_form_add_image').on("click", function(e) {
        e.preventDefault();
        
        $('div#add_image').hide(); 
        $('div#control_add_image').show();

    });

    $('.show_update_tender_image_caption').on("click", function(e) {
        e.preventDefault();
        
        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var elementId = myarr[1];
        
        $('div#update_tender_image_caption-' + elementId).show(); 
        $('div#control_update_tender_image-' + elementId).hide(); 
        $('p#tender_image_caption-' + elementId).hide();

    });
    
    $('a.hide_form_update_tender_image_caption').on("click", function(e) {
        e.preventDefault();
        
        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var elementId = myarr[1];
        
        $('div#update_tender_image_caption-' + elementId).hide(); 
        $('div#control_update_tender_image-' + elementId).show(); 
        $('p#tender_image_caption-' + elementId).show();

    });

    $('a.show_update_tender_term_condition').on("click", function(e) {
        e.preventDefault();

        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var elementId = myarr[1];
        
        $('div#update_tender_term_condition-' + elementId).show();
        $('p#tender_term_condition-' + elementId).hide(); 
        $('div#control_update_tender_term_condition-' + elementId).hide();

        
    });
    
    $('a.hide_form_update_tender_term_condition').on("click", function(e) {
        e.preventDefault();
        
        var idClick = $(this).attr('id');
        var myarr = idClick.split("-");
        var elementId = myarr[1];
        
        $('div#update_tender_term_condition-' + elementId).hide();
        $('p#tender_term_condition-' + elementId).show(); 
        $('div#control_update_tender_term_condition-' + elementId).show();


    });
    
    
    $(document).on('click', 'a.see_shortlist', function(e){
        e.preventDefault();
        show_bids_shortlist(this);

//        var idClick = $(this).attr('id');
//        var myarr = idClick.split("-");
//        var elementId = myarr[1];
//        var request = $.ajax({
//            url: '/tenders/see_shortlist',
//            type: "POST",
//            // dataType: "json",
//            data: {
//                id: elementId
//            },
//            cache: false
//        });
//        request.always(function (a) {
//            
//        });
//        request.done(function (data) {
//            $('#bids').html(data);
//        });
//        request.fail(function (jqXHR, textStatus, errorThrown) {
//        });

    });
    
    $(document).on('click', 'a.see_all_bids', function(e){
        e.preventDefault();
        see_all_bids(this);

//        var idClick = $(this).attr('id');
//        var myarr = idClick.split("-");
//        var elementId = myarr[1];
//        clicked_element = $(this).parent();
//
//        var request = $.ajax({
//            url: '/tenders/see_all_bids',
//            type: "POST",
//            // dataType: "json",
//            data: {
//                    Tender: {id: elementId }
//            },
//            cache: false
//        });
//        request.always(function (a) {
//            
//        });
//        request.done(function (data) {
//            $('#bids').html(data);
//        });
//        request.fail(function (jqXHR, textStatus, errorThrown) {
//        });

    });
    
        
    $(document).on('click', 'tr.clickable-bid', function(e){
        e.preventDefault();
        var href = $(this).attr('data-href');
        var myarr = href.split("/");
        var elementId = myarr[3];
        var alias = myarr[4];
        var request = $.ajax({
            url: '/bids/details',
            type: "POST",
            // dataType: "json",
            data: {
                Bid: { 
                    id: elementId,
                    contractor_alias: alias
                }
            },
            cache: false
        });
        request.always(function (a) {
            
        });
        request.done(function (data) {
            $('#bids').html(data);
            hide_form_choose_bid(data);
            
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
        });

        
        function hide_form_choose_bid(e) {
            if (e) {
                $('div#choose_bid').hide(); 
            }
        }

        
        
    });

    function show_bids_shortlist(e) {
        if(e) {

            var idClick = $(e).attr('id');
            var myarr = idClick.split("-");
            var elementId = myarr[1];
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
                $("#bids").html(data);
            });
            request.fail(function (jqXHR, textStatus, errorThrown) {
            });

            return false;

            
        }  
    }

    function see_all_bids(e) {
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
    
    

    $('.one-click-button').on('click',function()
    {
      $(this).val('Please wait ...')
        .attr('disabled','disabled');
      var form = $(this).parents('form:first');
      form.submit();
    });    

    var date = new Date();
    $('#date_begin').datetimepicker({
        language: 'en',
        pick12HourFormat: false,
        startDate: date,
    }, function(beginDate, endDate) {
    });
    
    $('#date_complete').datetimepicker({
        language: 'en',
        pick12HourFormat: false,
        startDate: date,
    }, function(beginDate, endDate) {
    });
    
    $('#extend_to_bids').datetimepicker({
        language: 'en',
        pick12HourFormat: false,
        startDate: date,
    }, function(beginDate, endDate) {
    });





});
