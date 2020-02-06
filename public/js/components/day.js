export default class Day {
  
        id ;
    constructor(id, output, days_names){
        this.id = id; //day-selector-
        this.content = `
            <div class="day-bundle-${id} col-sm-6 day" style='display:none;' key="${id}">
                <div class="day-selector-bundle-${id} ">
                    <label class="label-day-selector label" for="days">Dia</label>
                    <br>
                    <select class="form-control day" disabled="" name="day-${id}" id="select-${id}">
                    </select>
                </div>
                <div class="row hour-bundle-${id} ">
                    <div class="col h-on-bundle-0">
                        <label class="label-h-on" for="hour_on">Hora encendido</label>
                        <input type="time" class="h-on form-control text-center time" id="hour_on-${id}" disabled="" name="hour_on-${id}" value="12:00">
                    </div>
                    <div class="col h-off-bundle-${id} ">
                        <label class="label-h-off" for="hour_off">Hora apagado</label>
                        <input type="time" class="h-off form-control text-center time" id="hour_off-${id}" disabled="" name="hour_off-${id}" value="20:00">
                    </div>
                </div>
            </div>`;

        $('#days-bundle').prepend(this.content);
        let outDay;
        if(!this.isEmpty(output)){
            $(`#hour_on-${id}`).val(`${output.hour_on[id]}`);
            $(`#hour_off-${id}`).val(`${output.hour_off[id]}`);
            outDay = output.days[id];
        } else {
            outDay = 0;
        }

        let selected = '';
        for (let i = 0; i < days_names.length; i++) {
            if (outDay == i) {
                selected = 'selected';
            } else {
                selected = '';
            }
            $(`#select-${id}`).append(`<option value="${i}" ${selected}>${days_names[i]}</option>`)
        }
        this.id = id;
    }

    update(){

    }

    hide(){
        $(".day-bundle-"+this.id).slideUp();
    }

    show(){
        $(".day-bundle-"+this.id).slideDown();
    }
    
    showError(errorText){
        $('#program-error').text(errorText);
        $('#program-error').fadeIn();//No hay programas creados aun
    }
    isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
            return false;
        }
        return true;
    }
    enable(){
        $('.day').prop("disabled", false);
        $('.time').prop("disabled", false);
    }
    disable(){
        $('.day').prop("disabled", true);
        $('.time').prop("disabled", true);
    }

}