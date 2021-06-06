$(function () {

    /* LOGIN PANEL SCRIPTS +++++++++++++++++++++++++++++++++++++++++++++++++++ */
    $('.login-link').on('click',function () {
        $('.bg-login-panel, .login-panel').fadeIn(500)
        $('body').css('overflow','hidden')
    })
    $('.bg-login-panel, .close').on('click',function () {
        $('.bg-login-panel, .login-panel').fadeOut(500)
        $('body').css('overflow','')
    })
    
    /* FORM INPUTS SCRIPTS +++++++++++++++++++++++++++++++++++++++++++++++++++ */
    $('.form-control input').each(function () {
        if($(this).val()){
            $(this).parent().addClass('form-control-active')
        }
    })
    $('.form-control input').on('focus',function () {        
        $(this).parent().addClass('form-control-active')
    })
    $('.form-control input').on('blur',function () {
        if(!$(this).val()){
            $(this).parent().removeClass('form-control-active')
        }
    })

    $('.form-control .password-show').on('click',function () {        
        $(this).toggleClass('password-show-hidden')
        let input = $(this).parent().children('input')
        let type = input.attr('type') == 'password'? 'text' : 'password'
        input.attr('type', type)
    })

    $('.opener').opener();
})
