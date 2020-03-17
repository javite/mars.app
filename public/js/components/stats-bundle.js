import Graph from './graph.js';


export default class StatsBundle {

    constructor(){
        this.content = `<div class="graphs-container " id="graphs"></div>`;
        this.graphsArray = [];
        this.device_id = getCookie('device_id');
        $('#stats-container').append(this.content);
        let graph = new Graph("Historial de Temperatura", 0, 1);
        let graph2 = new Graph("Historial de Humedad", 1, 2);
        let graph3 = new Graph("Historial de Humedad tierra", 2, 3);

    }

    update(measurement){

    }

    hide(){
        $('#program-cont').slideUp();
    }

    show(){
        $('#program-cont').slideDown();

    }

    isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
            return false;
        }
        return true;
    }
  
}