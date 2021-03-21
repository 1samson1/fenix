$(function(){   
    $('.static-body img').each(function () {
        $(this).attr({style:'cursor:zoom-in'})
        $(this).on('click', function () {
            $("<div/>")
                .addClass('imagefuller')
                .attr({style: ''})
                .appendTo('body')
            $('<img/>')
                .attr({src: $(this).attr('src')})
                .appendTo('.imagefuller')
            $('.imagefuller').on('click', function () {
                $('.imagefuller').remove()    
            })
        })
    })   
})
