export default class cardMeasurement{

    content =`  <div class="card shadow bg-light text-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="text-left" id="title">Temperatura</h4>
                                <h5 class="text-left" id="sub-title">ambiente</h5>
                            </div>
                            <div class="card-value col">
                                <h3 class="card-text" id="value">00</h3><h3 class="card-text">ºC</h3>
                                <img src="images/thermometer.svg" id="img-temp"alt="">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted text-left" >Actualizado: <span id="updated_at">20-01-2020</span></small>
                    </div>
                </div>
            `;

    constructor(measurement){
        let thisClass = this;
        $('#deck-measurements').append(this.content);
        $('#value').text(measurement.temperature);
        $('#updated_at').text(measurement.updated_at);
        console.log(measurement);
        
    }

    update(){
        this.devices = devices;
        let selected;
        for (let index = 0; index < devices.length; index++) {
            if(this.firstTime){
                this.value = 0;
                this.firstTime = false;
            } 
            if(this.value == index){ 
                selected = 'selected';
            } else {
                selected = '';
            }
            this.selector.append(`<option value="${this.devices[index].id}" ${selected}>${this.devices[index].name}</option>`); 
        }
    }

    hide(){
        this.selector.slideUp();
    }

    show(){
        this.selector.show();

    }


}