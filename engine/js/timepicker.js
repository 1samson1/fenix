(function( $ ){

    class Timepicker{
        constructor(el, options){
            this.el = el;
            this.opts = options;
            this.template = $('<div class="timepicker"></div>');

            this.genHtml();
            this.template.insertAfter(this.el)
        }

        genHtml() {
            for (var i=this.opts.minHours; i<=this.opts.maxHours; i++){
                for (var j=0; j<=this.opts.step; j+=this.opts.step){
                    var clock = $(`<div class="clock">${i}:${('0'+j).slice(-2)}</div>`)
                    clock.on('click', this, function(e){    
                        $(e.data.template).children('.clock-selected').removeClass('clock-selected')                        
                        $(e.data.el).val($(this).html()) 
                        $(this).addClass('clock-selected')
                    })
                    this.template.append(clock)
                }
            }
        }

    }

    $.fn.timepicker = function( options ) {  
        var settings = $.extend({
            minHours: 8,
            maxHours: 17,
            step: 30,
        },options);        

  
        return this.each(function() {            
            if(this.nodeName == 'INPUT'){
                $.data(this,'timepicker', new Timepicker(this, settings));
            }
            else {
                console.error('This DOM element is not a input!', this)
            }    
        });
  
    };
})( jQuery );
