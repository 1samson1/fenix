$(function () {
    $('.opener').opener();
    $('select').niceSelect();

    $('.date').datepicker({
        dateFormat:"dd.mm.yyyy"
    });

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

    $('.form-control').each(function () {
        if($(this).val()){
            $(this).parent().addClass('form-group-active')
        }
    })
    $('.form-control').on('focus',function () {        
        $(this).parent().addClass('form-group-active')
    })
    $('.form-control').on('blur',function () {
        if(!$(this).val()){
            $(this).parent().removeClass('form-group-active')
        }
    })
    
    $('.form-group-password .password-show').on('click',function () {        
        $(this).toggleClass('password-show-hidden')
        let input = $(this).siblings('.form-control')
        let type = input.attr('type') == 'password'? 'text' : 'password'
        input.attr('type', type)
    })
})
