export default class deviceSelectorForm {

    content =`<form form action="main.php" method="get" class="form devices-list">
                <div class="form-group mx-sm-3">
                    <input type="hidden" name="device_id" value="0" >
                    <label for="devices">Growers</label>
                    <select name="device_id" id="device_selector" class="mdb-select form-control md-form colorful-select dropdown-primary">
                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary" value="submit">Seleccionar</button>
                </div>
            </form>
            `;

    constructor(){
        let thisClass = this;
        $('#main-container').prepend(this.content);
        this.selector = $('#device_selector');
        this.firstTime = true;
        this.value = 0;
        
    }

    update(devices){
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