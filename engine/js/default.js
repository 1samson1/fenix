$(function(){  
    jQuery.datetimepicker.setLocale('ru');  
    $('.date').datetimepicker({   
        timepicker:true,
        mask:true,
        step:30,
        format:'Y-m-d H:i',
        dayOfWeekStart:1,        
    });     
    $(".close").on("click" , function(){
        $(".modalWinRec").fadeOut(1000);
    });
    $(".fonModalWinRec").on("click" , function(){
        $(".modalWinRec").fadeOut(1000);
    });    
});

function createXHR(){
    var Request = false;

    if(window.XMLHttpRequest){
        Request = new XMLHttpRequest();
    }
    else{
        try{
            Request = new ActiveXObject('Microsoft.XMLHTTP');
        }
        catch{
            Request = new ActiveXObject('Msxml2.XMLHTTP');
        }
    }

    if(!Request){
        alert('Невозможно создать XHR');
    }

    return Request;
}  

function recDoc(doctor) { 
    $('#preloader').fadeIn();
    
    var xhr = createXHR();

    xhr.open("POST",'/engine/ajax/recDoc.php');
    
    xhr.responseType = 'json';
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onload = function(){
        complite(xhr.response);         
    }
    xhr.onerror = function(){
        console.error(xhr.response)
    }   

    let body = {
         doctor_id:doctor,
         date:getDate('date',doctor),       
    }

    xhr.send(parsePHP(body));
}   

function complite(param) {
    $('#preloader').fadeOut();    
    if(param.status == 'good'){   
        $(".modalWinRec").fadeToggle(1000);
        $(".titleModalWinRec").html("Талон успешно оформлен!");
        $(".thank").html("Пожалуйста не опаздывайте на приём.");
        $(".timeRec").html("<b>Ваше время:</b> " + param.date);
        $(".cabinet").html("<b>Кабинет №</b> " + param.kabinet + "<br><b>Имя врача:</b> " +  param.doctor_name + "<br><b>Специальность врача:</b> " +  param.specialty_title);          
    }
    else{
        var errorQUERY;
        switch (param.num_error) {
            case 1:
                errorQUERY = 'Вы ввели недействительную дату и время';
                break;
            
            case 1062:
                errorQUERY = 'На эту дату и время уже есть запись';
                break;

            default:
                errorQUERY = 'Что-то пошло не так , попробуйте позже';
                break;
        }
        $(".modalWinRec").fadeToggle(1000);
        $(".titleModalWinRec").html("Ошибка!");
        $(".thank").empty();
        $(".timeRec").empty();
        $(".cabinet").text(errorQUERY);
        console.log(errorQUERY)
    }
}

function parsePHP(object) {
    var param ='';
    for(let key in object){
        if(param){
            param+='&';
        }
        param+= key + '=' + object[key];
    }
    return param; 
}

function getDate(object,index) {
    return document.getElementsByClassName(object)[Number.parseInt(index)-1].value;
}