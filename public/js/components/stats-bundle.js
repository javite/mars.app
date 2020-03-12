import Graph from './graph.js';


export default class StatsBundle {

    constructor(){
        this.content = `<div class="card-deck" id="graphs"></div>`;
        this.graphsArray = [];
        this.device_id = getCookie('device_id');
        $('#stats-container').append(this.content);
        let graph = new Graph();

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