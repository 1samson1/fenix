jQuery( document ).ready(function($){
	var 
	speed = 500,
	$scrollTop = $("<a/>")
	  .addClass('scrollTop')
	  .attr({href:'#', style:'display:none; z-index:1; position:fixed;'})
	  .appendTo('body');		

	$scrollTop.click(function(e){
		e.preventDefault();

		$( 'html:not(:animated),body:not(:animated)' ).animate({ scrollTop: 0}, speed );
	});

	//появление
	function show_scrollTop(){
		( $(window).scrollTop() > 300 ) ? $scrollTop.fadeIn(600) : $scrollTop.fadeOut(600);
	}
	$(window).scroll( function(){ show_scrollTop(); } );
	show_scrollTop();
});