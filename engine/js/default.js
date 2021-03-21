$(function(){  
    $.datetimepicker.setLocale('ru');  
    $('.date').datetimepicker({   
        timepicker:true,
        mask:true,
        step:30,
        format:'Y-m-d H:i',
        dayOfWeekStart:1,        
    });     
    tinymce.init({
        selector: 'textarea'
    });  
});
