var pageInitialized = false;
$(document).ready(function () {
    if(pageInitialized) return;
    pageInitialized = true;

    $('div#update_service_types').hide(); 
    $('div#define_estimator').hide(); 
    
    $('a#update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_service_types').show(); 
        $('a#update_service_types').hide(); 

    });
    
    $('a#hide_form_update_service_types').on("click", function(e) {
        e.preventDefault();
        
        $('div#update_service_types').hide(); 
        $('a#update_service_types').show(); 

    });
    
    $('a#show_define_estimator').on("click", function(e) {
        e.preventDefault();
        
        $('div#define_estimator').show(); 
        $('p#estimator_info').hide(); 

    });
    
    $('a#hide_form_define_estimator').on("click", function(e) {
        e.preventDefault();
        
        $('div#define_estimator').hide(); 
        $('p#estimator_info').show(); 

    });
    
    var beginDate = new Date();
    var endDate = new Date();
    endDate.setDate(beginDate.getDate()+3);
    
    var month = beginDate.getMonth()+1;
    var day = beginDate.getDate();

    var beginDateString = ((''+month).length<2 ? '0' : '') + month + '-' +
        ((''+day).length<2 ? '0' : '') + day + '-' +
        beginDate.getFullYear();


    month = endDate.getMonth()+1;
    day = endDate.getDate();

    var endDateString = ((''+month).length<2 ? '0' : '') + month + '-' +
        ((''+day).length<2 ? '0' : '') + day + '-' +
        endDate.getFullYear();

    var date = new Date();

    $('#dispatch_date').datetimepicker({
        language: 'en',
        pick12HourFormat: false,
        startDate: date,
//        format: "MM/dd/yyyy", 
//        locale: {
//            format: 'MMM-d-yyyy'
//        },
    }, function(beginDate, endDate) {
    });
    
    $('#dispatch_time_begin').datetimepicker({
        language: 'en',
        pick12HourFormat: true,
        pickDate: false,
        pickSeconds: false,
    }, function(beginDate, endDate) {
      console.log("New date range selected: ' + start.format('MMMM/D/YYYY h:mm A') + ' to ' + end.format('YYYY-MM-DD h:mm A') ");
    });
    
    $('#dispatch_time_end').datetimepicker({
        language: 'en',
        pick12HourFormat: true,
        pickDate: false,
        pickSeconds: false,
    }, function(beginDate, endDate) {
      console.log("New date range selected: ' + start.format('MMMM/D/YYYY h:mm A') + ' to ' + end.format('YYYY-MM-DD h:mm A') ");
    });
    
//    $('#invoice_due_date').datetimepicker({
//        language: 'en',
//        months:[
//         'Jan','Feb','Mar','Apr',
//         'May','Jun','Jul','Aug',
//         'Sep','Oct','Nov','Dec',
//        ],
//        pick12HourFormat: false,
//        format: "MM/dd/yyyy", 
//
//    }, function(beginDate, endDate) {
//    });
//    
//    $('#invoice_issue_date').datetimepicker({
//        language: 'en',
//        months:[
//         'Jan','Feb','Mar','Apr',
//         'May','Jun','Jul','Aug',
//         'Sep','Oct','Nov','Dec',
//        ],
//        pick12HourFormat: false,
//        format: "MM/dd/yyyy", 
//
//    }, function(beginDate, endDate) {
//    });
//    
//    $('#invoice_receipt_date').datetimepicker({
//        language: 'en',
//        months:[
//         'Jan','Feb','Mar','Apr',
//         'May','Jun','Jul','Aug',
//         'Sep','Oct','Nov','Dec',
//        ],
//        pick12HourFormat: false,
//        format: "MM/dd/yyyy", 
//
//    }, function(beginDate, endDate) {
//    });
    
    $('.show_schedule_fields').on("click", function(e) {
        var clickVal = $(this).val();
        
        if (clickVal == 1) {
            $("#dispatch_date").removeClass('hidden');            
            $("#dispatch_time_begin").removeClass('hidden');    
        } else {
            $("#dispatch_time_begin").addClass('hidden');            
        }
    });
    

//    $('div#update_to').hide(); 
//    
//    $('a#show_update_to').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_to').show(); 
//        $('p#to').hide(); 
//        $('div#control_update_to').hide();
//
//    });
//    
//    $('a#hide_form_update_to').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_to').hide(); 
//        $('p#to').show(); 
//        $('div#control_update_to').show();
//
//    });
//
//    
//    $('div#update_for').hide(); 
//    
//    $('a#show_update_for').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_for').show(); 
//        $('p#for').hide(); 
//        $('div#control_update_for').hide();
//
//    });
//    
//    $('a#hide_form_update_for').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_for').hide(); 
//        $('p#for').show(); 
//        $('div#control_update_for').show();
//
//    });
//    
//    
//    $('div#update_due_date').hide(); 
//    
//    $('a#show_update_due_date').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_due_date').show(); 
//        $('p#due_date').hide(); 
//        $('div#control_update_due_date').hide();
//
//    });
//    
//    $('a#hide_form_update_due_date').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_due_date').hide(); 
//        $('p#due_date').show(); 
//        $('div#control_update_due_date').show();
//
//    });
//    
//    $('div#update_issue_date').hide(); 
//    
//    $('a#show_update_issue_date').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_issue_date').show(); 
//        $('p#issue_date').hide(); 
//        $('div#control_update_issue_date').hide();
//
//    });
//    
//    $('a#hide_form_update_issue_date').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_issue_date').hide(); 
//        $('p#issue_date').show(); 
//        $('div#control_update_issue_date').show();
//
//    });
//    
//    $('div#update_receipt_date').hide(); 
//    
//    $('a#show_update_receipt_date').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_receipt_date').show(); 
//        $('p#receipt_date').hide(); 
//        $('div#control_update_receipt_date').hide();
//
//    });
//    
//    $('a#hide_form_update_receipt_date').on("click", function(e) {
//        e.preventDefault();
//        
//        $('div#update_receipt_date').hide(); 
//        $('p#receipt_date').show(); 
//        $('div#control_update_receipt_date').show();
//
//    });
    

});
