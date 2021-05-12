$(function(){
    var el = $(".timerec"),
    minHours = 8,
    maxHours = 17,
    step = 30;
    
    timepicker = $(' <div class="timepicker"></div>  ')    
    el.after(timepicker)
     

    for (var i=minHours; i<=maxHours; i++){
        for (var j=0; j<=step; j+=step){
            clock = $(`<div class="clock">${i}:${('0'+j).slice(-2)}</div>`)
            clock.on('click', function(){
                timepicker.children('.clock-selected').removeClass('clock-selected')
                el.val($(this).html()) 
                $(this).addClass('clock-selected')
            })
            timepicker.append(clock)
        }
    }
    
})


