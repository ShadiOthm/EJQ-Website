$(document).ready(function () {

    $('.change_language').on("click", function(e) {
        e.preventDefault();

        id = $(this).attr('id');
        
        if (id == 'lang-pt-BR') {
            lang = 'pt-BR';
        }
        if (id == 'lang-eng') {
            lang = 'eng';
        }
        clicked_element = $(this).parent();

        var request = $.ajax({
            url: '/main/change_language/' + lang,
            type: "POST",
            // dataType: "json",
            data: {},
            cache: false
        });
        request.always(function (a) {
            
        });
        request.done(function (data) {
            //clicked_element.html(data);
            setTimeout(function(){// wait for 1/2 sec
                 location.reload(); // then reload the page.
            }, 500);             
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            
        });

    });
    
    
    
});