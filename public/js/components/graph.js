
export default class Graph {
        
    constructor(){
        this.content = `<div class="card shadow bg-light mb-3 text-center">
                            <h5 class="card-header" id="titulo">Historial de Temperatura y Humedad ambiente</h5>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="temp_chart"></canvas>
                                    <script src="js/line.js"></script>
                                </div>
                                <div class="container-fluid">
                                    <div class="row justify-content-md-center">
                                        <div class="input-group col-md-6">
                                            <input type="date" class="form-control" id="date_chart_temp_hum" >
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" onclick="loadData()">Actualizar</button>
                                            </div>
                                            <div class="alert alert-warning" id="alerta" role="alert">
                                                <strong>UPS! </strong> No hay datos el dia seleccionado.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Actualizado: TODO</small>
                            </div>
                    </div>`;
        $('#graphs').append(this.content);
    }

    update(){

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