import deviceSelectorForm from './device-selector-form.js';
import cardMeasurement from './card-measurement.js';

export default class HomeBundle {

    content = `
            <div class="card-deck" id="deck-measurements"></div>
            <div class="card-deck" id="deck-outputs"></div>
            `;

    constructor(){
        let thisclass = this;
        $('#main-container').append(this.content);
        let DeviceSelectorForm = new deviceSelectorForm();
        let devices = this.getDevices();
        devices.then(data => {
            DeviceSelectorForm.update(data);
            console.log('Devices: ',data);
            let lastMeasurement = this.lastMeasurement();
            lastMeasurement.then(data=>{
                new cardMeasurement(data);
            })
            
        })

    }

    update(){

    }

    getDevices(){
        return fetch("getDevices")
        .then(data =>data.json())
        .catch(error => console.error(error))
    }

    lastMeasurement(){
        return fetch("getLastMeasurement")
        .then(data =>data.json())
        .catch(error => console.error(error))
    }

    hide(){
        $('#program-cont').slideUp();
    }

    show(){
        $('#program-cont').slideDown();

    }

  
}