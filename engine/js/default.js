$(function(){     
    tinymce.init({
        selector: 'textarea',
        language:"ru",
        mode : "textareas",
        plugins:"link, image, paste",
        statusbar:false,
        menubar:false,
        branding: false,  
        paste_as_text:true,  
        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | link image",
    });  
});
