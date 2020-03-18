import deviceSelectorForm from './device-selector-form.js';
import cardMeasurement from './card-measurement.js';
import cardOuput from './card-output.js';

export default class HomeBundle {

    constructor(){
        this.content = `<div class="card-deck" id="deck-measurements"></div>
                        <div class="card-deck" id="deck-outputs"></div>`;
        let thisclass = this;
        this.cardArray = [];
        this.cardArrayOuput = [];
        this.device_id;
        $('#home-container').append(this.content);
        let DeviceSelectorForm = new deviceSelectorForm();

        DeviceSelectorForm.event().change(()=>{
            this.device_id = DeviceSelectorForm.event().val();
            setCookie('device_id', this.device_id, 1);
            console.log('device id: ', this.device_id);
            let lastMeasurementUpdate = this.lastMeasurement(this.device_id );
            lastMeasurementUpdate.then(data=>{
                if(!this.isEmpty(data)){
                    this.update(data);
                    console.log(data);
                    DeviceSelectorForm.hideMessage();
                } else {
                    DeviceSelectorForm.message('No hay mediciones de este dispositivo');
                }
            })
        })

        let devices = this.getDevices();
        devices.then(data => {
            this.devices = data;
            this.device_id = this.devices[0].id;
            setCookie('device_id', this.device_id, 1);
            DeviceSelectorForm.update(data);
            console.log('Devices: ',data);
            if(this.devices.length>0){
                let lastMeasurement = this.lastMeasurement(this.device_id);
                lastMeasurement.then(data=>{
                    console.log(data);
                    for (let index = 0; index < 3; index++) {
                        this.cardArray[index] = new cardMeasurement(data, index);
                    }
                    for (let index = 0; index < 3; index++) {
                        this.cardArrayOuput[index] = new cardOuput(index);
                    }
                })
            }
        })
    }

    update(measurement){
        let array = [measurement.temperature, measurement.humidity, measurement.soil_humidity_1];
        for (let index = 0; index < this.cardArray.length; index++) {
            this.cardArray[index].update(array[index], measurement.updated_at);
        }
    }

    getDevices(){
        return fetch("getDevices")
        .then(data =>data.json())
        .catch(error => console.error(error))
    }

    lastMeasurement(device_id){
        return fetch(`getLastMeasurement/${device_id}`)
        .then(data =>data.json())
        .catch(error => console.error(error))
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