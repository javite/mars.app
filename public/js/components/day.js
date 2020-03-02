import IconOutputBundle from './icon-bundle-output.js';

export default class Day {
        content = `
        <div class="day-bundle col-sm-6 day" style='display:none;' id='day' key="0">
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
        id ;
        
    constructor(day, days_names){
        let day_selected;
        this.day = day;
         $('#days-bundle').prepend(this.content);

        if(!this.isEmpty(day)){
            this.id = this.day.id;
            $(`#hour_on`).val(`${day.hour_on}`);
            $(`#hour_off`).val(`${day.hour_off}`);
            $(`.day[key=0]`).attr('key',`${this.id}`);
            day_selected = this.day.day;
        } else {
            console.log('empty day');
            this.id = 0;
            day_selected = 0;
        }

        let selected = '';
        for (let i = 0; i < days_names.length; i++) {
            if (day_selected == i) {
                selected = 'selected';
            } else {
                selected = '';
            }
            $(`#select`).append(`<option value="${i}" ${selected}>${days_names[i]}</option>`)
        }
        this.self = $(`.day[key=${this.id}]`);

        /*ICONO EDIT */
        this.iconEdit = new IconOutputBundle();
        this.iconEdit.clickEvent().click(()=>{
            if(this.iconEdit.getState()){
                this.enable();
            } else{
                this.disable();
            }
            
        });
        this.iconEdit.show();
    }

    update(day){
        this.day = day;
        this.id = this.day.id;
        $(`#hour_on`).val(`${this.day.hour_on}`);
        $(`#hour_off`).val(`${this.day.hour_off}`);
        $(`.day[key=0]`).attr('key',`${this.id}`);
        $(`#select`).val(this.day.day);
        this.self = $(`.day[key=${this.id}]`);
        console.log('upadte day')
        this.iconEdit.show();
    }

    hide(){
        this.self.slideUp();
    }

    show(){
        this.self.slideDown();
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
        this.self.find("input").prop("disabled", false);
        this.self.find("select").prop("disabled", false);
    }

    disable(){
        this.self.find("input").prop("disabled", true);
        this.self.find("select").prop("disabled", true);
    }

}