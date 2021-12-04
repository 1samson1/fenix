const self = document.currentScript;
const url = new URL(self.src);
const params = url.searchParams;

var maxDate = new Date(),
    minDate = new Date(),
    $specialty = $('#specialty'),
    $doctor = $('#doctor'),
    $datetime = $('#time'),
    doctors = [],
    doctor_id = null,
    range = {},
    appointments = [];

maxDate.setDate(maxDate.getDate() + 56);

// Init

$datetime.datestable({
    timestamp: true,
    minDate,
    maxDate,
    onChangeRange: function(date1, date2, table){
        table.disabled = true;
        range.min = date1;
        range.max = date2;

        if(doctor_id){
            fetch_appointments(doctor_id, range.min, range.max);
        }
    },
    onRenderTime: function (date){
        var time = date.getTime(),
            busy = minDate.getTime() <= time && time < maxDate.getTime();
            disDay = false;
            
        if(doctor_id){
            var doctor = doctors.filter( value => value.id == doctor_id)[0],
                day = date.getDay();

            const days = [ doctor.sun, doctor.mon, doctor.tue, doctor.wed, doctor.thu, doctor.fri, doctor.sat ];
            
            disDay = days[day] == 0;
        }

        busy = appointments.indexOf(time) > -1 && busy;

        return {
            busy: busy && !disDay,
            disabled: busy || disDay,
        }
    }
});

fetch_doctors.call($specialty);

// Events

$specialty.on('change', fetch_doctors);
$doctor.on('change', function(){
    doctor_id = $(this).val();
    fetch_appointments(doctor_id, range.min, range.max);
    
});

// Utils

function fetch_doctors(){
    fetch_data('/api/doctors/?specialty=' + $(this).val())
        .then(docs => {
            doctors = docs;
            genOptionSelect($doctor, docs);
        })    
}

function fetch_appointments(doctor, minDate, maxDate){
    var min = minDate.getTime().toString().slice(0,-3);
    var max = maxDate.getTime().toString().slice(0,-3);

    fetch_data('/api/appointments/?doctor='+ doctor +' &mindate='+ min +'&maxdate='+ max)
        .then(appoints => {
            var table = $datetime.data('datestable');

            appointments = appoints.map(function(value){
                return parseInt(value.time + '000');       
            });
            table.disabled = false;
            table.update();
        })
}

function genOptionSelect(el, arr){
    var html = '',
        selected ='';

    for (var i = 0; i < arr.length; i++){
        selected = arr[i].id == params.get('doctor') ? 'selected' : '';
        html += '<option value="' + arr[i].id + '"' + selected + '>'+ arr[i].name + '</option>';
    }

    el.html(html);
    el.trigger('change');
    $doctor.niceSelect('update');
}

async function fetch_data(url, body, method = "GET"){
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
    }

    if(method != 'GET'){
        options[body] = body;
    }

    return await fetch(url, options)
        .then(reply => reply.json())
        .then(resp => resp.response)
}
