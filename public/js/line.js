
var chart_1;

function createChart(){

    var ctx_line = document.getElementById('temp_chart').getContext('2d');
    var data_line = [0, 0, 0, 0];
    var data_line2 = [0, 0, 0, 0];
    
    chart_1 = new Chart(ctx_line, {
        type: 'line',
        data: {
            labels: ["1", "2", "3","4"],
            datasets: [{
                label: 'Temperatura',
                type: 'line',
                data: data_line,
                backgroundColor: [
                    'rgba(103, 195, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(103,195,255,1)'
                ],
                borderWidth: 3,
                fill: false,
                pointRadius: 1,
                pointHoverRadius: 3,
                yAxisID: 'y_temp',
                showLine: true, // no line shown
                steppedLine: false, //before, after, middle, true, false
                //borderDash: [5,5], //para hacer punteada 
                //interpolacion -> //lineTension: 0 //cubicInterpolationMode: 'monotone'
            },
            {
                label: 'Humedad',
                type: 'line',
                data: data_line2,
                backgroundColor: [
                    'rgba(153, 100, 100, 0.6)'
                ],
                borderColor: [
                    'rgba(153,100,100,1)'
                ],
                borderWidth: 3,
                fill: false,
                pointRadius: 1,
                pointHoverRadius: 3,
                yAxisID: 'y_hum',
                showLine: true, // no line shown
                //steppedLine: false, //before, after, middle, true, false
                //cubicInterpolationMode: 'monotone'
                //borderDash: [5,5], //para hacer punteada 
                //interpolacion -> //lineTension: 0 //cubicInterpolationMode: 'monotone'
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            responsiveAnimationDuration: 400,
            cutoutPercentage: 50,
            hoverMode: 'index',
            stacked: false,
            scales: {
                yAxes: [{
                    display: true,
                    type: 'linear',
                    position: 'left',
                    scaleLabel: {
                        labelString: 'Grados',
                        display: true
                    },
                    ticks:{
                        min:0,
                        max:60,
                        stepSizes: 5
                    },
                    id: 'y_temp'
                },{
                    display: true,
                    type: 'linear',
                    position: 'right',
                    scaleLabel: {
                        labelString: '% humedad',
                        display: true
                    },
                    ticks:{
                        min:0,
                        suggestedMax:100,
                        stepSizes: 5
                    },
                    id: 'y_hum'
            }],
                xAxes: [{
                    type: 'time',
                    display: true,
                }]
            },
            layout: {padding: 1},
            legend: {
                display: true,
                position: 'bottom',
                fullWidth: true,
                labels: {fontColor: 'rgb(100, 100, 100)',
                        boxWidth: 10,
                        fontSize: 12
                }
            },
            title: {
                display: false,
                text: 'TITULO'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },

        }
    });
};

function updateDataChar() {

    let date_chart = $("#date_chart_temp_hum").val();
    let device_id = getCookie('device_id');
    let limit = 1000;
    if(device_id == null){
        error("no hay device id");
    } else {
    let url = `getMeasurements?device_id=${device_id}&date=${date_chart}%200:00:00&limit=${limit}`;
    console.log(url);
    fetch(url)
        .then(response => {
            if(response.status != 200){             
                return null;
            } else {
                return response.json();
            }
        })
        .then(response => {
            console.log(response);
            if (response.length == 0){
                $("#alerta").show();
            } else {
                response.forEach((data,index) => {
                    chart_1.data.labels[index] = data.created_at;
                    chart_1.data.datasets[0].data[index] = data.temperature;
                    chart_1.data.datasets[1].data[index] = data.humidity;
                })
                $("#alerta").hide();
                chart_1.clear();
                chart_1.update();
            }
        })
        .catch(error => console.error(error))
    }
}

function loadData() {
    createChart();
    updateDataChar();
}

