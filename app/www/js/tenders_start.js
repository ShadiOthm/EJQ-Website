$(document).ready(function () {
    $('form#cancel_request').hide(); 
    
    var beginDate = new Date();
    
    
    var month = beginDate.getMonth()+1;
    var day = beginDate.getDate();

    var beginDateString = ((''+month).length<2 ? '0' : '') + month + '/' +
        ((''+day).length<2 ? '0' : '') + day + '/' +
        beginDate.getFullYear();
    
    $('input[class="schedule_date"]').daterangepicker({
        "singleDatePicker": true,
        "timePicker": false,
        locale: {
            format: 'MM-DD-YYYY'
        },
        "startDate": beginDateString,
        "minDate": 0,

    }, function(start, end, label) {
      console.log("New date range selected: ' + start.format('YYYY-MM-DD HH:MM') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
    });
    
    
    
    $('button#start_tender').on("click", function(e) {
        e.preventDefault();
        
        $('form#start_tender').show(); 
        $(this).hide();

    });
    
    $('a#hide_form_start_tender').on("click", function(e) {
        e.preventDefault();
        
        $('form#start_tender').hide(); 
        $('button#start_tender').show();

    });
        

    $('button#cancel_request').on("click", function(e) {
        e.preventDefault();
        
        $('form#cancel_request').show(); 
        $(this).hide();

    });
    
    $('a#hide_form_cancel_request').on("click", function(e) {
        e.preventDefault();
        
        $('form#cancel_request').hide(); 
        $('button#cancel_request').show();

    });
    
    $(".chosen_select").chosen({width: "95%"});
        
});
