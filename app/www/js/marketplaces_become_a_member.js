var pageInitialized = false;
$(document).ready(function () {
    if(pageInitialized) return;
    pageInitialized = true;

    $("input.phone").mask("999-999-9999");
    
    $(".terms_of_service").colorbox({inline:true, width:"80%"});
});
