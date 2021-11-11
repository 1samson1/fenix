document.addEventListener('DOMContentLoaded', function (e) {
    var slider = document.querySelector('.slider'),
        slides = document.querySelector(".slides"),
        slides_list = document.querySelectorAll(".slide"),
        currentSlide = 0;

    setInterval(() => {
        next_slide();
    },5000)

    resize_slides();

    window.addEventListener('resize', resize_slides);
    
    function next_slide() {
        if(slides_list.length-1 > currentSlide)
            currentSlide++;
        else 
            currentSlide = 0;   
        
        slides.style.transform = `translateX(-${slider.offsetWidth*currentSlide}px)`;
    }

    function resize_slides(){
        var width_slider = slider.offsetWidth;

        slides.style.transform = `translateX(-${width_slider*currentSlide}px)`;

        for(var i = 0; i < slides_list.length; i++){
            slides_list[i].style.width = width_slider+"px";
        }
    }
});
