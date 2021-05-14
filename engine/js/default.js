$(function(){     
    tinymce.init({
        selector: 'textarea',
        language:"ru",
        mode : "textareas",
        plugins:"link, image",
        statusbar:false,
        menubar:false,
        branding: false,    
        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | link image",
    });  
});
