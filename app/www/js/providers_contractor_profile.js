$(document).ready(function () {
    $('div#update_service_types').hide(); 
    $("input.year").mask("9999");
    $("input.phone").mask("999-999-9999");
    
    $('a#show_update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_service_types').show(); 
        $('p#admin_services_list').hide(); 
        $(this).hide();

    });
    
    $('a#hide_form_update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_service_types').hide(); 
        $('p#admin_services_list').show(); 
        $('a#show_update_service_types').show();

    });
    
    $('div#update_about_info').hide(); 
    
    $('a#show_update_about_info').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_about_info').show(); 
        $('div#about_info').hide(); 

    });
    
    $('a#hide_form_update_about_info').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_about_info').hide(); 
        $('div#about_info').show(); 

    });

    
    $('div#update_contact_info').hide(); 
    
    $('a#show_update_contact_info').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_contact_info').show(); 
        $('div#contact_info').hide(); 

    });
    
    $('a#hide_form_update_contact_info').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_contact_info').hide(); 
        $('div#contact_info').show(); 

    });

    $('div#update_company_disclosure').hide(); 
    
    $('a#show_update_company_disclosure').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_company_disclosure').show(); 
        $('div#company_disclosure').hide(); 

    });
    
    $('a#hide_form_update_company_disclosure').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_company_disclosure').hide(); 
        $('div#company_disclosure').show(); 

    });

    
    $('div#update_licences').hide(); 
    
    $('a#show_update_licences').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_licences').show(); 
        $('div#licences').hide(); 

    });
    
    $('a#hide_form_update_licences').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_licences').hide(); 
        $('div#licences').show(); 

    });
    
    
    $('div.force_show').show(); 

    $('#update_company_qualifying').unbind().on("click", "input.toggle_qualify", function(e) {
        e.preventDefault(e);
        toggle_qualify(this);
    });
    
    function toggle_qualify(e) {
        if (e) {
            
            var contractorIdInput = $("#ContractorId").val();
            clicked_element = $(e).parent();
            var request = $.ajax({
                url: '/contractors/toggle_qualify',
                type: "POST",
                // dataType: "json",
                data: {
                    Contractor: {id: contractorIdInput},
                },
                cache: false
            });
            request.always(function (a) {

            });
            request.done(function (data) {
                $('#update_company_qualifying').html(data);
            });
            request.fail(function (jqXHR, textStatus, errorThrown) {
            });

            return false;
        }
    }

    $('.one-click-checkbox').on('click',function()
    {
      $(this).val('Please wait ...')
//        .attr('disabled','disabled');
//      var form = $(this).parents('form:first');
//      form.submit();
    });    

    
    
});
