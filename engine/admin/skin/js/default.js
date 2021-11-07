$(function () { 
    $('.opener').opener();
    $('select').niceSelect();
    $('.date').datepicker({
        dateFormat:"dd.mm.yyyy"
    });

    /* FORM INPUTS SCRIPTS +++++++++++++++++++++++++++++++++++++++++++++++++++ */
    $('.form-group-password .password-show').on('click',function () {
        $(this).toggleClass('password-show-hidden')
        let input = $(this).siblings('.form-control')
        let type = input.attr('type') == 'password'? 'text' : 'password'
        input.attr('type', type)
    })

    tinymce.init({
        selector: 'textarea',
        language:"ru",
        mode : "textareas",
        width:"100%",
        height:500,                
        plugins: ["advlist autolink lists link image filemanager charmap anchor searchreplace visualblocks visualchars media nonbreaking table emoticons paste spellchecker codesample hr fullscreen"],
        statusbar:false,
        menubar:false,
        relative_urls:false,
        branding: false,  
        paste_as_text:true,
        image_dimensions: false,
        toolbar1: "formatselect fontselect fontsizeselect | link anchor unlink | image filemanager | codesample hr visualblocks code | spellchecker removeformat searchreplace fullscreen",
        toolbar2: "undo redo | copy paste pastetext | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | subscript superscript | table bullist numlist | forecolor backcolor",
    });
})
