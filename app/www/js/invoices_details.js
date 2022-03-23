var pageInitialized = false;
$(document).ready(function () {
    if(pageInitialized) return;
    pageInitialized = true;

    $('.one-click-button').on('click',function()
    {
      $(this).val('Please wait ...')
        .attr('disabled','disabled');
      var form = $(this).parents('form:first');
      form.submit();
    });    

    var beginDate = new Date();
    var endDate = new Date();
    endDate.setDate(beginDate.getDate()+3);
    
    $('#invoice_due_date').datetimepicker({
        language: 'en',
        months:[
         'Jan','Feb','Mar','Apr',
         'May','Jun','Jul','Aug',
         'Sep','Oct','Nov','Dec',
        ],
        pick12HourFormat: false,
        format: "MM/dd/yyyy", 

    }, function(beginDate, endDate) {
    });
    
    $('#invoice_issue_date').datetimepicker({
        language: 'en',
        months:[
         'Jan','Feb','Mar','Apr',
         'May','Jun','Jul','Aug',
         'Sep','Oct','Nov','Dec',
        ],
        pick12HourFormat: false,
        format: "MM/dd/yyyy", 

    }, function(beginDate, endDate) {
    });
    
    $('#invoice_receipt_date').datetimepicker({
        language: 'en',
        months:[
         'Jan','Feb','Mar','Apr',
         'May','Jun','Jul','Aug',
         'Sep','Oct','Nov','Dec',
        ],
        pick12HourFormat: false,
        format: "MM/dd/yyyy", 

    }, function(beginDate, endDate) {
    });

    $('div#update_to').addClass('hidden'); 
    
    $('a#show_update_to').on("click", function(e) {
        e.preventDefault();
        
        $("div#update_to").removeClass('hidden');            
        $('p#to').addClass('hidden'); 
        $('div#control_update_to').addClass('hidden');

    });
    $('a#hide_form_update_to').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_to').addClass('hidden'); 
        $('p#to').removeClass('hidden'); 
        $('div#control_update_to').removeClass('hidden');

    });

    
    $('div#update_for').addClass('hidden'); 
    
    $('a#show_update_for').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_for').removeClass('hidden'); 
        $('p#for').addClass('hidden'); 
        $('div#control_update_for').addClass('hidden');

    });
    
    $('a#hide_form_update_for').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_for').addClass('hidden'); 
        $('p#for').removeClass('hidden'); 
        $('div#control_update_for').removeClass('hidden');

    });
    
    
    $('div#update_due_date').addClass('hidden'); 
    
    $('a#show_update_due_date').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_due_date').removeClass('hidden'); 
        $('p#due_date').addClass('hidden'); 
        $('div#control_update_due_date').addClass('hidden');

    });
    
    $('a#hide_form_update_due_date').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_due_date').addClass('hidden'); 
        $('p#due_date').removeClass('hidden'); 
        $('div#control_update_due_date').removeClass('hidden');

    });
    
    $('div#update_issue_date').addClass('hidden'); 
    
    $('a#show_update_issue_date').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_issue_date').removeClass('hidden'); 
        $('p#issue_date').addClass('hidden'); 
        $('div#control_update_issue_date').addClass('hidden');

    });
    
    $('a#hide_form_update_issue_date').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_issue_date').addClass('hidden'); 
        $('p#issue_date').removeClass('hidden'); 
        $('div#control_update_issue_date').removeClass('hidden');

    });
    
    $('div#update_receipt_date').addClass('hidden'); 
    
    $('a#show_update_receipt_date').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_receipt_date').removeClass('hidden'); 
        $('p#receipt_date').addClass('hidden'); 
        $('div#control_update_receipt_date').addClass('hidden');

    });
    
    $('a#hide_form_update_receipt_date').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_receipt_date').addClass('hidden'); 
        $('p#receipt_date').removeClass('hidden'); 
        $('div#control_update_receipt_date').removeClass('hidden');

    });
    

});
