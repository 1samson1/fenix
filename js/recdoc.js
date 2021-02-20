$(function(){
    $(".edit-profile").on("click" , function(){
        $(".controls").slideToggle(1000);
    });  

    setTimeout(function() {
        $(".kabinet-complete").slideUp();
        $(".complete").slideUp();
    }, 10000);    

    function getRandomArbitary(min, max)
    {
        return Math.round(Math.random() * (max - min) + min);
    }
});