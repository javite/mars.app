
export default class Graph {
        
    constructor(title, type, sensor_id){
        this.sensor_id = sensor_id;
        this.type = type;
        this.content = `<div class="shadow bg-light mb-3 text-center graph card" sensor="${sensor_id}">
                            <h5 class="card-header" >${title}</h5>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="${sensor_id}"></canvas>
                                </div>
                                <div class="container-fluid">
                                    <div class="row justify-content-center">
                                        <input type="date" class="form-control date" id="date_chart">
                                        <div class="container-date-submit">
                                            <button class="btn btn-primary" type="button" id="update">Actualizar</button>
                                        </div>
                                        <div class="alert alert-warning" id="alerta" role="alert">
                                            <strong>UPS! </strong> No hay datos el dia seleccionado.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Actualizado: TODO</small>
                            </div>
                        </div>`;
        $('#graphs').append(this.content);
        this.self = $(`[sensor|=${this.sensor_id}]`);
        this.self.find('#update').click(()=>this.update()); // evento del boton actualizar
        let date = new Date();
        console.log(date);
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();

        if(month < 10){
            month = '0' + month;
        }
        if(day < 10){
            day = '0' + day;
        }
        
        let complete_date = `${year}-${month}-${day}`;
        this.self.find("#date_chart").val(complete_date); //pone fecha del dia actual

        let parameter;
        let unit;
        let background_color;
        let border_color;
        let scale;
        switch (this.type) {
            case 0:
                parameter = 'Temperatura';
                unit = 'Grados';
                background_color = 'rgba(103, 195, 255, 0.6)';
                border_color = 'rgba(103,195,255,1)';
                scale = 60;
                break;
            case 1:
                parameter = 'Humedad';
                unit = '%';
                background_color = 'rgba(153, 100, 100, 0.6)';
                border_color = 'rgba(153,100,100,1)';
                scale = 100;
                break;
            case 2:
                parameter = 'Humedad tierra';
                unit = '%';
                background_color = 'rgba(53, 50, 50, 0.6)';
                border_color = 'rgba(53,50,50,1)';
                scale = 100;
                break;
            default:
                parameter = 'no title';
                unit = 'no unit';
                break;
        }
        this.context = this.self.find('canvas');
        this.data_line = [0, 0, 0, 0];
        this.chart_1 = new Chart(this.context, {
            type: 'line',
            data: {
                labels: ["1", "2", "3","4"],
                datasets: [{
                    label: parameter,
                    type: 'line',
                    data: this.data_line,
                    backgroundColor: [
                        background_color
                    ],
                    borderColor: [
                        border_color
                    ],
                    borderWidth: 3,
                    fill: true,
                    pointRadius: 1,
                    pointHoverRadius: 3,
                    yAxisID: 'y_temp',
                    showLine: true, // no line shown
                    steppedLine: false, //before, after, middle, true, false
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
                            labelString: unit,
                            display: true
                        },
                        ticks:{
                            min:0,
                            max: scale,
                            stepSizes: 5
                        },
                        id: 'y_temp'
                    }],
                    xAxes: [{
                        type: 'time',
                        display: true,
                    }]
                },
                layout: {
                    padding: 1
                },
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
                }
            }
        })

        this.device_id = getCookie('device_id');
        this.update();
    }

    update(){
        let chart = this.updateDataChar();
        chart.then(response => {
            console.log(response);
            if (response.length == 0){
                this.self.find("#alerta").show();
            } else {
                response.forEach((measure,index) => {
                    this.chart_1.data.labels[index] = measure.created_at;
                    this.chart_1.data.datasets[0].data[index] = measure.data;
                })
                this.self.find("#alerta").hide();
                this.chart_1.clear();
                this.chart_1.update();
            }
        })
        .catch(error => console.error(error))
    }

    updateDataChar() {
        let date_chart = this.self.find("#date_chart").val();
        let limit = 1000;
        if(this.device_id == null){
            error("no hay device id");
        } else {
            let url = `getSensorMeasurements?sensor_id=${this.sensor_id}&date=${date_chart}%200:00:00&limit=${limit}`;
            console.log(url);
            return fetch(url).then(response => response.json())
        }
    }

    hide(){
        this.self.slideUp();
    }

    show(){
        this.self.slideDown();
    }
    
    showError(errorText){
        $('#alerta').text(errorText);
        $('#alerta').fadeIn();//No hay programas creados aun
    }


}