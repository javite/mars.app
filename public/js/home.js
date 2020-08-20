var IP =window.location.hostname ;//"192.168.1.40"
var url = "ws://" + IP + ":1337/"; //192.168.4.1:1337/ window.location.hostname
// var url_program = "http://" + IP + "/loadProgram";
var url_http = "http://" + IP;
var button;
var indicator;
var context;
var date_init_1;
var days_duration_1;
var date_end_1;
var hour_init_1;
var hour_duration_1;
var date_init_2;
var days_duration_2;
var date_end_2;
var hour_init_2;
var hour_duration_2;
var form_program;

function getLampsRefs(){
    $('#lamps-container').click(function(event){
        IP = $(event.target).attr("ip");
        console.log(IP);
        if (IP !== ""){
            $('#program-container').slideDown();
            url_http = "http://" + IP;
            url = "ws://" + IP + ":1337/";
            init();
        }
    })
}

// This is called when the page finishes loading
function init() {
    // Assign page elements to variables
    button = document.getElementById("toggleButton");
    indicator = document.getElementById("indicator");
    /*FORM */
    form_program = document.getElementById("form-program");
    form_program.addEventListener('submit',clickOnSubmit);

    /*FECHA INICIO 1*/
    date_init_1 = document.getElementById("fecha-inicio-1");
    date_init_1.addEventListener('change',updateDate1);
    date_init_2 = document.getElementById("fecha-inicio-2");
    date_init_2.addEventListener('change',updateDate2);
    days_duration_1 = document.getElementById("duracion-1");
    days_duration_1.addEventListener('change',updateDate1);
    
    /*FECHA FIN 1*/
    date_end_1 = document.getElementById("fecha-fin-1");

    /*FECHA INICIO 2 */
    days_duration_2 = document.getElementById("duracion-2");
    days_duration_2.addEventListener('change',updateDate2);

    /*FEHCA FIN 2*/
    date_end_2 = document.getElementById("fecha-fin-2");

    /*HORA INICIO 1 */
    hour_init_1 = document.getElementById("hora-inicio-1");
    hour_init_1.addEventListener('change',updateTime1);
    hour_duration_1 = document.getElementById("duracion-hora-1");
    hour_duration_1.addEventListener('change',updateTime1);

    /*HORA INICIO 2 */
    hour_init_2 = document.getElementById("hora-inicio-2");
    hour_init_2.addEventListener('change',updateTime2);
    hour_duration_2 = document.getElementById("duracion-hora-2");
    hour_duration_2.addEventListener('change',updateTime2);

    // Connect to WebSocket server
    wsConnect(url);

    fetch(url_http + "/getProgram")
    .then(res=>res.json())
    .catch(error => console.error('Error: ', error))
    .then(response => {
        console.log('Succes: ', response);
        updateUI(response);
    })
}

function updateUI(program){
    /*DATES*/
    let fecha_inicio_1 = new Date(program["photo_periods"][0].init_period * 1000);
    let year = fecha_inicio_1.getFullYear();
    let month = fecha_inicio_1.getMonth() + 1;
    let day  = fecha_inicio_1.getDate();
    if(day < 10){
        day = "0" + day;
    }
    if(month < 10){
        month = "0" + month;
    }
    date_init_1.value = year + "-" + month + "-" + day;

    let fecha_fin_1 = new Date(program["photo_periods"][0].end_period * 1000);
    year = fecha_fin_1.getFullYear();
    month = fecha_fin_1.getMonth() + 1;
    day  = fecha_fin_1.getDate();
    if(day < 10){
        day = "0" + day;
    }
    if(month < 10){
        month = "0" + month;
    }
    date_end_1.value = year + "-" + month + "-" + day;
    document.getElementById("fecha-fin-1-indicator").value = date_end_1.value;

    days_duration_1.value = Math.abs((fecha_inicio_1 - fecha_fin_1)/1000/60/60/24);

    let fecha_inicio_2 = new Date(program["photo_periods"][1].init_period * 1000);
    year = fecha_inicio_2.getFullYear();
    month = fecha_inicio_2.getMonth() + 1;
    day  = fecha_inicio_2.getDate();
    if(day < 10){
        day = "0" + day;
    }
    if(month < 10){
        month = "0" + month;
    }
    date_init_2.value = year + "-" + month + "-" + day;

    let fecha_fin_2 = new Date(program["photo_periods"][1].end_period * 1000);
    year = fecha_fin_2.getFullYear();
    month = fecha_fin_2.getMonth() + 1;
    day  = fecha_fin_2.getDate();
    if(day < 10){
        day = "0" + day;
    }
    if(month < 10){
        month = "0" + month;
    }
    date_end_2.value = year + "-" + month + "-" + day;
    document.getElementById("fecha-fin-2-indicator").value = date_end_2.value;

    days_duration_2.value = Math.abs((fecha_inicio_2 - fecha_fin_2)/1000/60/60/24);

    /*TIMES*/
    let hora_on_1 = program["photo_periods"][0].ouputs[0].hours_on[0];
    let hora = Math.floor(hora_on_1);
    if(hora < 10){
        hora = "0" + hora;
    }
    let minutos = hora_on_1 - hora;
    minutos = Math.round(minutos / 0.0166);
    if(minutos < 10){
        minutos = "0" + minutos;
    }
    hora_on_1 = hora + ":" + minutos;
    hour_init_1.value = hora_on_1;

    let hora_off_1 = program["photo_periods"][0].ouputs[0].hours_off[0];
    hora = Math.floor(hora_off_1);
    minutos = hora_off_1 - hora;
    minutos = Math.round(minutos / 0.0166);
    if(hora < 10){
        hora = "0" + hora;
    }
    if(minutos < 10){
        minutos = "0" + minutos;
    }
    hora_off_1 = hora + ":" + minutos;
    document.getElementById("hora-fin-1").value = hora_off_1;
    hour_duration_1.value = calculateDuration(hora_on_1, hora_off_1);

    let hora_on_2 = program["photo_periods"][1].ouputs[0].hours_on[0];
    hora = Math.floor(hora_on_2);
    minutos = hora_on_2 - hora;
    minutos = Math.round(minutos / 0.0166);
    if(hora < 10){
        hora = "0" + hora;
    }
    if(minutos < 10){
        minutos = "0" + minutos;
    }
    hora_on_2 = hora + ":" + minutos;
    hour_init_2.value = hora_on_2;    

    let hora_off_2 = program["photo_periods"][0].ouputs[0].hours_off[0];
    hora = Math.floor(hora_off_2);
    minutos = hora_off_2 - hora;
    minutos = Math.round(minutos / 0.0166);
    if(hora < 10){
        hora = "0" + hora;
    }
    if(minutos < 10){
        minutos = "0" + minutos;
    }
    hora_off_2 = hora + ":" + minutos;
    document.getElementById("hora-fin-2").value = hora_off_2;
    hour_duration_1.value = calculateDuration(hora_on_1, hora_off_1);
}

function updateDate1(){
    let fecha = addDays(date_init_1.value  + " 00:00:00", days_duration_1.value);
    let month = fecha.getMonth() + 1;
    let day  = fecha.getDate();
    if(day < 10){
        day = "0" + day;
    }
    if(month < 10){
        month = "0" + month;
    }
    let fecha_fin = fecha.getFullYear() + "-" + month + "-" + day;
    date_end_1.value = fecha_fin;
    document.getElementById("fecha-fin-1-indicator").value = fecha_fin;
    date_init_2.value = fecha_fin; //SET FECHA INICIO 2
    updateDate2();
}

function updateDate2(){
    let fecha = addDays(date_init_2.value  + " 00:00:00", days_duration_2.value);
    let month = fecha.getMonth() + 1;
    let day  = fecha.getDate();
    if(day < 10){
        day = "0" + day;
    }
    if(month < 10){
        month = "0" + month;
    }
    let fecha_fin = fecha.getFullYear() + "-" + month + "-" + day;
    date_end_2.value = fecha_fin;
    document.getElementById("fecha-fin-2-indicator").value = fecha_fin;
}

function updateTime1(){
    let hora = addTime(hour_init_1.value, hour_duration_1.value);
    console.log(hora);
    let hour = hora.getHours();
    let minutes  = hora.getMinutes();
    if(hour < 10){
        hour = "0" + hour;
    }
    if(minutes < 10){
        minutes = "0" + minutes;
    }
    let hora_fin = hour + ":" + minutes;
    document.getElementById("hora-fin-1").value = hora_fin; 
}

function updateTime2(){
    let hora = addTime(hour_init_2.value, hour_duration_2.value);
    console.log(hora);
    let hour = hora.getHours();
    let minutes  = hora.getMinutes();
    if(hour < 10){
        hour = "0" + hour;
    }
    if(minutes < 10){
        minutes = "0" + minutes;
    }
    let hora_fin = hour + ":" + minutes;
    document.getElementById("hora-fin-2").value = hora_fin; 
}

function addDays(fecha_, days) {
    let result = new Date(fecha_.replace(/-/g, '/'));
    let d = result.getDate() + parseInt(days);
    result.setDate(d);
    return result;
}

function addTime(hour_, hour_plus) {
    var splitTime1= hour_.split(':');
    let result = new Date(0,0,0,splitTime1[0],splitTime1[1],0,0);
    let d = result.getHours() + parseInt(hour_plus);
    result.setHours(d);
    return result;
}

function calculateDuration(hora_on_1, hora_off_1){
    var splitTime1= hora_on_1.split(':');
    let init_hour = parseInt(splitTime1[0]);

    var splitTime2= hora_off_1.split(':');
    let end_hour = parseInt(splitTime2[0]);
    let difference;
    if(end_hour > init_hour){
        difference = end_hour - init_hour;
    } else {
        difference = 24 - (init_hour - end_hour);
    }
    return difference;
}

function clickOnSubmit(event){
    event.preventDefault();
    let program = {"id":1,"device_id":1,"name":"Lechuga","created_at":"2020-08-01 00:00:00","updated_at":"2020-08-02 01:01:01","photo_periods":[{"init_period":52100000,"end_period":52100000,"ouputs":[{"timerMode":1,"days":[7],"hours_on":[11],"hours_off":[20],"out":1,"name":"Iluminacion 1"}]},{"init_period":52100000,"end_period":52100000,"ouputs":[{"timerMode":1,"days":[7],"hours_on":[11],"hours_off":[20],"out":1,"name":"Iluminacion 1"}]}]};
    let data = {};
    const formData = new FormData(form_program);
    for (let tuple of formData.entries()) data[tuple[0]] = tuple[1];

    let date_temp = data["init_period_1"] + " 00:00:00";
    var date = new Date(date_temp.replace(/-/g, '/'));
    data["init_period_1"] = date.getTime();

    date_temp = data["end_period_1"] + " 00:00:00";
    date = new Date(date_temp.replace(/-/g, '/'));
    data["end_period_1"] = date.getTime();

    date_temp = data["init_period_2"] + " 00:00:00";
    date = new Date(date_temp.replace(/-/g, '/'));
    data["init_period_2"] = date.getTime();

    date_temp = data["end_period_2"] + " 00:00:00";
    date = new Date(date_temp.replace(/-/g, '/'));
    data["end_period_2"] = date.getTime();
    console.log(data);
    program["photo_periods"][0]["init_period"] = data["init_period_1"]/1000;
    program["photo_periods"][1]["init_period"] = data["init_period_2"]/1000;
    program["photo_periods"][0]["end_period"] = data["end_period_1"]/1000;
    program["photo_periods"][1]["end_period"] = data["end_period_2"]/1000;
    
    let temp = data["hour_on_1"].split(':'); 

    program["photo_periods"][0]["ouputs"][0]["hours_on"][0] = parseFloat(temp[0]) + parseFloat((temp[1] * 0.016666).toFixed(2));
    temp = data["hour_off_1"].split(':');
    program["photo_periods"][0]["ouputs"][0]["hours_off"][0] = parseFloat(temp[0]) + parseFloat((temp[1] * 0.016666).toFixed(2));
    temp = data["hour_on_2"].split(':');
    program["photo_periods"][1]["ouputs"][0]["hours_on"][0] = parseFloat(temp[0]) + parseFloat((temp[1] * 0.016666).toFixed(2));
    temp = data["hour_off_2"].split(':');
    program["photo_periods"][1]["ouputs"][0]["hours_off"][0] = parseFloat(temp[0]) + parseFloat((temp[1] * 0.016666).toFixed(2));

    console.log(JSON.stringify(program));

    fetch(url_http + "/loadProgram",{
        method: 'POST',
        body: JSON.stringify(program),
        mode: 'cors',
        headers:{
            'Content-Type': 'application/json'
        }
    })
    .then(res=>res.json())
    .catch(error => console.error('Error: ', error))
    .then(response => console.log('Succes: ', response))
}

/********WEBSOCKETS**********/
// Call this to connect to the WebSocket server
function wsConnect(url) {
    
    // Connect to WebSocket server
    websocket = new WebSocket(url);
    
    // Assign callbacks
    websocket.onopen = function(evt) { onOpen(evt) };
    websocket.onclose = function(evt) { onClose(evt) };
    websocket.onmessage = function(evt) { onMessage(evt) };
    websocket.onerror = function(evt) { onError(evt) };
}

// Called when a WebSocket connection is established with the server
function onOpen(evt) {

    // Log connection state
    console.log("Connected");
    
    // Enable button
    button.disabled = false;
    
    // Get the current state of the LED
    doSend("getLEDState");
}

// Called when the WebSocket connection is closed
function onClose(evt) {

    // Log disconnection state
    console.log("Disconnected");
    
    // Disable button
    button.disabled = true;
    
    // Try to reconnect after a few seconds
    setTimeout(function() { wsConnect(url) }, 2000);
}

// Called when a message is received from the server
function onMessage(evt) {

    // Print out our received message
    console.log("Received: " + evt.data);  
    // Update circle graphic with LED state
    switch(evt.data) {
        case "0":
            console.log("LED is off");
            indicator.style.backgroundColor = "#000000a6";
            break;
        case "1":
            console.log("LED is on");
            indicator.style.backgroundColor = "#ffffffa6";
            break;
        default:
            break;
    }
}

// Called when a WebSocket error occurs
function onError(evt) {
    console.log("ERROR: " + evt.data);
}

// Sends a message to the server (and prints it to the console)
function doSend(message) {
    console.log("Sending: " + message);
    websocket.send(message);
}

// Called whenever the HTML button is pressed
function onPress() {
    doSend("toggleLED");
    doSend("getLEDState");
}


// Call the init function as soon as the page loads
// window.addEventListener("load", getLampsRefs, false);

        