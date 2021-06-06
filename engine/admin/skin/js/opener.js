(function( $ ){    

    $.fn.opener = function( options ) {
  
        return this.each(function() {
            // Open/close
            $(this).on('click', function(event) {
                var $opener = $(this);
                
                $opener.toggleClass('open');
            });

            // Close when resize window
            $(window).on('resize', this, function (event) {
                $(event.data).removeClass('open');
            }) 
            
            // Close when clicking outside
            $(document).on('click', this, function(event) {                
                if ($(event.target).closest(event.data).length === 0) {
                    $(event.data).removeClass('open');
                }
            });
        });  
    };
})( jQuery );
