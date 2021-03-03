$(function () {
    $('#login-link').on('click',function () {
        $('.bg-login-panel, .login-panel').fadeIn(500)
        $('body').css('overflow','hidden')
    })
    $('.bg-login-panel, .close').on('click',function () {
        $('.bg-login-panel, .login-panel').fadeOut(500)
        $('body').css('overflow','')
    })

    $('.form-input input').each(function () {
        if($(this).val()){
            $(this).parent().addClass('form-input-active')
        }
    })
    $('.form-input input').on('focus',function () {        
        $(this).parent().addClass('form-input-active')
    })
    $('.form-input input').on('blur',function () {
        if(!$(this).val()){
            $(this).parent().removeClass('form-input-active')
        }
    })

    $('.form-input .password-show').on('click',function () {        
        $(this).toggleClass('password-show-hidden')
        let input = $(this).parent().children('input')
        let type = input.attr('type') == 'password'? 'text' : 'password'
        input.attr('type', type)
    })

    $('.menu-icon-wrapper').opened = false
    $('.menu-icon-wrapper').on('click',function () {        
        this.opened = !this.opened
        $(this).children('.menu-icon').toggleClass('menu-icon-active')
        $(this).parent().parent().children(".links").toggleClass('links-active')
    })    
    $('.menu-icon-wrapper').on('blur',function () {   
        if(this.opened){     
            $(this).children('.menu-icon').removeClass('menu-icon-active')
            $(this).parent().parent().children(".links").removeClass('links-active')
            this.opened = false
        }
    })

    $('#user-panel').opened = false
    $('#user-panel').on('click',function () {
        this.opened = !this.opened
        $(this).parent().children('.user-options').toggleClass('user-options-open')
        
    })    
    $('#user-panel').on('blur',function () {   
        if(this.opened){
            $(this).parent().children('.user-options').removeClass('user-options-open')
            this.opened = false
        }
    })
})
