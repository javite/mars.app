export default class Day {
  
        id ;
    constructor(day, days_names){
        this.day = day; //day-selector-
        if(this.day.id){
            this.id = this.day.id;
        } else {
            this.id = 0;
        }
        
        this.content = `
            <div class="day-bundle col-sm-6 day" style='display:none;' key="${this.id}">
                <div class="day-selector-bundle">
                    <label class="label-day-selector label" for="days">Dia</label>
                    <br>
                    <select class="form-control day" disabled="" name="day" id="select">
                    </select>
                </div>
                <div class="row hour-bundle">
                    <div class="col h-on-bundle">
                        <label class="label-h-on" for="hour_on">Hora encendido</label>
                        <input type="time" class="h-on form-control text-center time" id="hour_on" disabled="" name="hour_on" value="12:00">
                    </div>
                    <div class="col h-off-bundle ">
                        <label class="label-h-off" for="hour_off">Hora apagado</label>
                        <input type="time" class="h-off form-control text-center time" id="hour_off" disabled="" name="hour_off" value="20:00">
                    </div>
                </div>
            </div>`;

        $('#days-bundle').prepend(this.content);
        if(!this.isEmpty(day)){
            $(`#hour_on`).val(`${day.hour_on}`);
            $(`#hour_off`).val(`${day.hour_off}`);
        }

        let selected = '';
        for (let i = 0; i < days_names.length; i++) {
            if (day.day == i) {
                selected = 'selected';
            } else {
                selected = '';
            }
            $(`#select`).append(`<option value="${i}" ${selected}>${days_names[i]}</option>`)
        }
        this.sefl = $(".day-bundle key[${this.id}]").
    }

    update(){

    }

    hide(){
        $(".day-bundle").slideUp();
    }

    show(){
        $(".day-bundle").slideDown();
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