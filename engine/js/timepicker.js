(function( $ ){
    var pluginName = 'timepicker';

    class Timepicker{
        constructor(el, options){
            this.el = el;
            this.opts = options;
            this.template = $('<div class="timepicker"></div>');

            this.template.insertAfter(this.el);
            this.genClock();
        }

        update(opts){
            $(this.el).val('');
            this.opts = $.extend(this.opts, opts);
            this.template.html('');
            this.genClock();
        }

        genClock() {
            for (var i=this.opts.minHours; i<=this.opts.maxHours; i++){
                for (var j=0; j<=this.opts.step; j+=this.opts.step){
                    var time = `${i}:${('0'+j).slice(-2)}`,
                        clock = $(`<div class="clock">${time}</div>`);
                        
                    if(!this.opts.disable && !this.opts.disableClock.includes(time)){
                        clock.on('click', this, function(e){    
                            $(e.data.template).children('.clock-selected').removeClass('clock-selected')                        
                            $(e.data.el).val($(this).html()) 
                            $(this).addClass('clock-selected')
                        })
                    } else {
                        clock.addClass('disable');
                    }
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
            disable: true,
            disableClock: []
        },options);

        
  
        return this.each(function() {   
            
            if(!$.data(this, pluginName))

                if(this.nodeName == 'INPUT'){
                    $.data(this, pluginName, new Timepicker(this, settings));
                }
                else {
                    console.error('This DOM element is not a input!', this)
                }

            else {
                var _this = $.data(this, pluginName);

                _this.opts = $.extend(true, _this.opts, options);
                _this.update();
            }   
        });
  
    };
})( jQuery );
