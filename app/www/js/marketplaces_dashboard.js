var pageInitialized = false;
$(document).ready(function () {

    if(pageInitialized) return;
    pageInitialized = true;
    
$.fn.dataTable.moment( 'MMM D, YYYY' );
    $('#table_requests').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_requests_length, div#table_requests_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_in_progress').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_in_progress_length, div#table_in_progress_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_open_to_bids').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_open_to_bids_length, div#table_open_to_bids_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_closed_to_bids').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_closed_to_bids_length, div#table_closed_to_bids_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_jobs').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_jobs_length, div#table_jobs_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_invoices').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_invoices_length, div#table_invoices_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_contractors').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_contractors_length, div#table_contractors_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_estimators').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_estimators_length, div#table_estimators_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    $('#table_consumers').DataTable({
        "order": [],
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        fnDrawCallback: function( oSettings ) {
        $('div#table_consumers_length, div#table_consumers_filter').parent().addClass("col-md-6 col-sm-6 col-xs-6");
        },
    });
    
    $('.cancel_demand').on("click", function(e) {
        e.preventDefault();

        var id = $(this).attr('id');
        var clicked_td = $(this).parent();
        
        var request = $.ajax({
            url: '/demands/cancel/' + id,
            type: "POST",
            // dataType: "json",
            data: {},
            cache: false
        });
        request.always(function (a) {
            
        });
        request.done(function (data) {
            clicked_td.html(data);
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            
        });


    });
    
    });