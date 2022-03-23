$(document).ready(function () {
        var date = new Date();

    $('#extend_to_bids').datetimepicker({
        language: 'en',
        pick12HourFormat: false,
        startDate: date,
    }, function(beginDate, endDate) {
    });





});
