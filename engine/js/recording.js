const self = document.currentScript;
const url = new URL(self.src);
const params = url.searchParams;

// Get data of doctor
fetch_data('/api/doctors/?doctor=' + params.get('doctor'))
    .then(doctor => renderCalendar(doctor));

function renderCalendar(doctor) {

    // Work days of doctor
    const days = [ doctor.sun, doctor.mon, doctor.tue, doctor.wed, doctor.thu, doctor.fri, doctor.sat ];

    // Init datepicker
    $('.daterec').datepicker({
        adaptive:false,
        mask:false,
        inline:true,
        minDate:new Date(new Date().setDate(new Date().getDate() + 1)),
        maxDate: new Date(new Date().setDate(new Date().getDate() + 56)),
        // Disable days aren't working
        onRenderCell: function (date, cellType) {
            if (cellType == 'day') {
                var day = date.getDay(),
                    isDisabled = days[day] == 0;
    
                return {
                    disabled: isDisabled
                }
            }
        },
        // When click cell then update timepicker
        onSelect: function (text, date, picker){
            // Disable timepicker when load data
            $('.timerec').data('timepicker').update({ disable: true });
            
            // Select or cansel select date
            if(date){
                //Convert millisecond to second with rounding
                const time = ~~(date.getTime() / 1000);
                
                // Get appointments are busy
                fetch_data('/api/appointments/?doctor=' + doctor.id + '&time=' + time)
                    .then(dates => {
                        // Convert timestamp to time
                        dates = dates.map((date) => {
                            var d = new Date(parseInt(`${date.time}000`))
                            return d.getHours() + ':' + firstZero(d.getMinutes())
                        })
                        
                        //Set disable clock
                        $('.timerec').data('timepicker').update({
                            disable: false,
                            disableClock : dates
                        });
                    });
            }
        }
    })

    // Init timepicker
    $('.timerec').timepicker({ disable: true });
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
