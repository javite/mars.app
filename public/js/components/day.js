import IconOutputBundle from './icon-bundle-output.js';
import DaysSubmit from './days-submit.js';

export default class Day {
        
    constructor(day, days_names){
        this.content = `<div class="day-bundle col-sm-6 day border_1" style='display:none;' id='0'>
                            <form class="form-day">
                                <input type="hidden" id="day_id" name="day_id" value=0>
                                <div class='error' id='erase-day' style='display: none'>¿Seguro que queres borrar el dia?</div>
                                <div class="day-sub-bundle">
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
                                </div>
                            </form>
                        </div>`;
        this.id ;
        let thisClass = this;
        let day_selected;
        let emptyDay = true;
        this.day = day;
         $('#days-bundle').prepend(this.content);

        if(!this.isEmpty(day)){
            this.id = this.day.id;
            $('#hour_on').val(`${day.hour_on}`);
            $('#hour_off').val(`${day.hour_off}`);
            $('#day_id').val(`${this.id}`);
            $('#0').attr('id',`${this.id}`);
            day_selected = this.day.day;
            emptyDay = false;
        } else {
            console.log('empty day');
            this.id = 0;
            day_selected = 7;
            emptyDay = true;
        }

        let selected = '';
        for (let i = 0; i < days_names.length; i++) {
            if (day_selected == i) {
                selected = 'selected';
            } else {
                selected = '';
            }
            $('#select').append(`<option value="${i}" ${selected}>${days_names[i]}</option>`)
        }
        this.self = $(`#${this.id}`);
    
        /*SUBMIT*/
        this.daysSubmit = new DaysSubmit(this.self);

        /*ICONO EDIT */
        this.iconEdit = new IconOutputBundle(this.self);
        this.iconEdit.show();
    }

    update(day){
        this.day = day;
        this.id = this.day.id;
        this.self.siblings('#hour_on').val(`${this.day.hour_on}`);
        this.self.siblings('#hour_off').val(`${this.day.hour_off}`);
        this.self.siblings('#select').val(this.day.day);
        $('#day_id').val(`${this.id}`);
        $('#0').attr('id',`${this.id}`);
        this.self = $(`#${this.id}`);
    }

    getRef(){
        return this.iconEdit.getRef();
    }

    hide(){
        this.self.slideUp();
    }

    show(){
        this.self.slideDown();
    
    }
    delete(){
        this.self.remove();
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

}