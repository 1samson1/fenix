window.onload = function () {
    let slider = document.querySelector('.slider')
    let slides = document.querySelector(".slides")
    let slides_list = document.querySelectorAll(".slide")
    let currentSlide = 0    

    setInterval(() => {
        next_slide()
    },5000)

    function next_slide() {
        if(slides_list.length-1 > currentSlide)
            currentSlide++
        else 
            currentSlide = 0        
        
        slides.style.transform = `translateX(-${slider.offsetWidth*currentSlide}px)`    
    }
    
    this.onresize = function () {
        slides.style.transform = `translateX(-${slider.offsetWidth*currentSlide}px)`    
    }
}
