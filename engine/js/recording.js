const self = document.currentScript;
const url = new URL(self.src);
const params = url.searchParams;

fetch_data('/api/doctors/?doctor=' + params.get('doctor'))
    .then(doctor => renderCalendar(doctor));

function renderCalendar(doctor) {

    //Work days of doctor
    const days = [ doctor.sun, doctor.mon, doctor.tue, doctor.wed, doctor.thu, doctor.fri, doctor.sat ];

    //Set datepicker
    $('.daterec').datepicker({
        adaptive:false,
        mask:false,
        inline:true,
        minDate:new Date(new Date().setDate(new Date().getDate() + 1)),
        maxDate: new Date(new Date().setDate(new Date().getDate() + 56)),
        onRenderCell: function (date, cellType) {
            if (cellType == 'day') {
                var day = date.getDay(),
                    isDisabled = days[day] == 0;
    
                return {
                    disabled: isDisabled
                }
            }
        },
        onSelect: function (text, date, picker){
            const time = ~~(date.getTime() / 1000);
            
            fetch_data('/api/appointments/?doctor=' + doctor.id + '&time=' + time)
                .then(dates => {
                    dates = dates.map((date) => {
                        var d = new Date(parseInt(`${date.time}000`))
                        return d.getHours() + ':' + firstZero(d.getMinutes())
                    })

                    $('.timerec').data('timepicker').update({
                        disable: false,
                        disableClock : dates
                    });
                });
        }
    })

    $('.timerec').timepicker();
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

function firstZero(value){
    return ('0' + value).slice(-2);
}
