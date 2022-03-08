$(function(){     
    tinymce.init({
        selector: 'textarea',
        language:"ru",
        mode : "textareas",
        plugins:"link, image, paste",
        deprecation_warnings: false,
        statusbar:false,
        menubar:false,
        branding: false,  
        paste_as_text:true,  
        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | link image",
    });  
});

/* CANCAL APPOINTMENT */

function cancelappointment(number) {
    ModalWindow.init().open(
        'Вы действительно хотите отменить запись на приём?',
        () => {
            submit("do_cancal_appointment", number)
        }
    )
}

function submit(action, pn, method = 'POST') {

    var form;
    
    form = document.createElement("form");
    form.setAttribute("method", method);
    form.style.display = 'none';

    var act = document.createElement('input');
    act.setAttribute('type', 'hidden');
    act.setAttribute('name', action);
    form.appendChild(act);

    document.body.appendChild(form);
   
    var pni = document.createElement("input");
    pni.setAttribute("type", "hidden");
    pni.setAttribute("name", 'param');
    pni.setAttribute("value", pn);

    form.appendChild(pni);

    form.submit();

    return false;
}
