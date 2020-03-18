export default class cardMeasurement{

    constructor(measurement, type){
        let thisClass = this;
        let title;
        let sub_title; 
        let value = measurement;
        let unit;
        let img_card;
        let id;

        switch (type) {
            case 0:
                title = 'Temperatura';
                sub_title = 'ambiente'; 
                // value = measurement;
                unit = '°C';
                img_card = 'images/thermometer.svg';
                id = `temperature_${type}`;
                break;
            case 1:
                title = 'Humedad';
                sub_title = 'ambiente'; 
                // value = measurement;
                unit = '%';
                img_card = 'images/drop.svg';
                id = `humidity_${type}`;
                break;
            case 2:
                title = 'Humedad';
                sub_title = 'de tierra'; 
                // value = measurement;
                unit = '%';
                img_card = 'images/soil_hum.svg';
                id = `soil_humidity_${type}`;
                break;
            default:
                break;
        }

        this.content =`  <div class="card shadow bg-dark text-success text-center" id="${id}">
                            <h5 class="card-header" id="title">${title} ${sub_title}</h5>
                            <div class="card-body">
                                    <div class="card-value ">
                                        <h1 class="card-text text-light"><span id="value">${value}</span> <span id="unit">${unit}</span></h1>
                                        <img src=${img_card} id="img-temp" alt="">
                                    </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted text-left" >Actualizado: <span id="updated_at">${measurement.updated_at}</span></small>
                            </div>
                        </div>
                    `;
        $('#deck-measurements').append(this.content);
        this.self = $(`#${id}`);

    }

    update(value, updated_at){
        this.self.find('#value').text(value);
        this.self.find('#updated_at').text(updated_at);
    }

    hide(){
        this.selector.slideUp();
    }

    show(){
        this.selector.show();

    }


}
/*<div class="card shadow bg-light text-center" id="${id}">
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="text-left" id="title">${title}</h4>
                                        <h5 class="text-left" id="sub-title">${sub_title}</h5>
                                    </div>
                                    <div class="card-value col">
                                        <h3 class="card-text"><span id="value">${value}</span> <span id="unit">${unit}</span></h3>
                                        <img src=${img_card} id="img-temp" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted text-left" >Actualizado: <span id="updated_at">${measurement.updated_at}</span></small>
                            </div>
                        </div> */