$(document).ready(function () {

    var date = new Date();

    $('#date_end').datetimepicker({
        language: 'en',
        pick12HourFormat: false,
        startDate: false,
        endDate: date,
    }, function(beginDate, endDate) {
    });
    


});
