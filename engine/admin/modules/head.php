<?php 

if(!$head['title']){
    $head['title'] = 'Админпанель';
}

$head = <<<HTML
<title>{$head['title']}</title>  

<script src="/engine/js/jquery-3.5.1.min.js"></script>

<link rel="stylesheet" href="{SKIN}/css/style.css">    
<script src="{SKIN}/js/default.js"></script>

<script src="{SKIN}/js/opener.js"></script>

<link rel="stylesheet" href="{SKIN}/css/nice-select.css">    
<script src="{SKIN}/js/jquery.nice-select.js"></script>

<script src="/engine/js/tinymce/tinymce.min.js"></script>

<link rel="stylesheet" href="/engine/css/filemanager.css">    
<script src="/engine/js/filemanager.js"></script>
<script>
    $(function(){     
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
    });
</script>
HTML;
?>