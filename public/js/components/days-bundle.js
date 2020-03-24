import Day from './day.js';
import AddDayButton from './add-day-button.js';


export default class DaysBundle {
    

    constructor(days, output_name_id, config){
        
        this.days = days;
        this.days_names = config.getDaysNames();
        this.config = config;
        this.output_name_id = output_name_id;
        this.content = "<div class='row days-bundle' id='days-bundle' style='display:none;'></div>";
        this.days_array = [];
        this.days_array_row = [];
        this.id = 0;
        var that = this;

        $('.out-bundle').append(this.content); 
        $('.out-bundle').append("<div class='error' id='error-empty-output' style='display: none'>La salida no tiene programa aun.</div>");
        this.errorMsg =  $('#error-empty-output');

        /*ADD DAY BUTTON*/
        this.addDay = new AddDayButton();

        /*DAYS ARE CREATED IN AN ARRAY*/
        if(this.days[this.id].length != 0){
            this.errorMsg.fadeOut();
            for (let i = 0; i < this.days.length; i++) {
                this.days_array_row = [];
                for (let j = 0; j < this.days[i].length; j++) {
                    this.days_array_row[j] = new Day(this.days[i][j], this.config.days_names);
                }
                this.days_array[i] = this.days_array_row;
            }
            console.log(this.days_array);
            for (let index = 0; index < this.days_array[0].length; index++) {
                this.days_array[0][index].show();
            }
        }

        /*EVENTS BUTTTONS EDIT, DELETE, CANCEL, SUBMIT, ADD*/
        this.addDayClick = this.addDay.click();
        this.addDayClick.click(()=>this.newDay());

        this.self = $('#days-bundle');

        this.self.on('click','#cancel-day', function(){
            if(that.flagNewDay == 'newDay'){
                that.cancelNew($(this));
            } else {
                that.cancel($(this));
            }
        })

        this.self.on('click','#btn-edit-day', function(){
            that.flagNewDay = 'saveDay';
            that.edit($(this));
        });

        this.self.on('click','#btn-erase-day', function(){
            that.flagNewDay = 'deleteDay';
            that.delete($(this));
        })

        this.self.on('click','#submit', function(){
            that.submit($(this));
        })

        this.checkDOM(); //check if add button must be show.
    }

    edit(control){
        control.parent().slideUp(); //oculta barra botones
        control.parent().siblings('.submit-bundle').slideDown(); //muestra barra submit
        control.parent().parent().parent().find("#msg-add").slideUp(); //oculta el +
        control.parent().parent().parent().find("#error-empty-output").slideUp(); //oculta el mensaje
        control.parent().parent().find("input").prop("disabled", false); //habilita controles
        control.parent().parent().find("select").prop("disabled", false); //habilita controles
        console.log('edit');
    }

    delete(control){
        console.log(control.parent().parent())
        control.parent().slideUp(); //oculta barra botones
        control.parent().siblings('.submit-bundle').slideDown(); //muestra barra submit
        control.parent().parent().find('.form-day').find('.day-sub-bundle').slideUp(); //oculta dia y hora
        control.parent().parent().find('.form-day').find('#erase-day').slideDown(); //muestra mensaje borrar
        control.parent().parent().parent().find("#msg-add").slideUp(); //oculta el +
        control.parent().parent().parent().find("#error-empty-output").slideUp(); //oculta el mensaje
        console.log('delete');
    }

    cancel(control){
        control.parent().siblings('#icon-bundle-output').slideDown(); //muestra barra botones
        control.parent().slideUp(); //oculta barra submit 
        control.parent().siblings('.day-sub-bundle').slideDown(); //muestra dia y hora
        control.parent().siblings('#erase-day').slideUp(); //oculta el mensaje de borrar
        control.parent().parent().find("input").prop("disabled", true); //deshabilita controles
        control.parent().parent().find("select").prop("disabled", true); //deshabilita controles
        console.log('cancel');
        this.checkDOM();
    }

    submit(control){
        let func;
        let form = control.parent().parent().find('form').serialize();
        let id = control.parent().parent().find('input[name="day_id"]').val();
        let token = $('#program-form').find('input[name="_token"]').val();
        let output_id = $('#out').val();
        form = `${form}&_token=${token}&output_id=${output_id}`;
        func = this.flagNewDay;
        console.log(form, func);
        if(func == 'deleteDay'){
            $.post(func, form)
                .done(()=>{
                    let _day_deleted = this.days_array[this.id].find((day)=>day.id==id);
                    _day_deleted.delete();
                    this.cancel(control); //acomoda interfaz.
                    console.log('Day updated: ', _day_deleted);
                })
                .fail((err)=>console.log('Error al borrar dia: ',err))
        } else if (func == 'saveDay'){
            $.post(func, form)
                .done((day_updated)=>{
                    let _day_updated = this.days_array[this.id].find((day)=>day.id==id);
                    _day_updated.update(day_updated);
                    this.cancel(control); //acomoda interfaz.
                    console.log('Day updated: ', day_updated);
                })
                .fail((err)=>console.log('Error al actualizar dia: ',err))
        }else if (func == 'newDay'){
            $.post(func, form)
                .done((day_created)=>{
                    this.cancel(control); //acomoda interfaz.
                    let length = this.days_array[this.id].length - 1;
                    this.days_array[this.id][length].update(day_created);
                    console.log('New day: ', day_created);
                })
                .fail((err)=>console.log('Error al crear un dia: ',err))
        }
    }

    update(output_name_id){
        this.output_name_id = output_name_id;
        this.id = this.output_name_id - 1;
        /*OCULTA TODOS LOS DAYS */
        for (let i = 0; i < this.days_array.length; i++) {  
            for (let j = 0; j < this.days_array[i].length; j++) {
                this.days_array[i][j].hide();
            }
        }
        /*MUESTRA SOLO DAYS DEL OUTPUT SELECCIONADO */
        console.log(this.id)
        for (let index = 0; index < this.days_array[this.id].length; index++) {
            this.days_array[this.id][index].show();
        }
        this.checkDOM();
    }

    checkDOM(){
        if(this.days_array[this.id].length == 0){
            this.errorMsg.fadeIn();
            this.addDay.show();
        } else {
            if(this.days_array[this.id][0].day.day == 7){
                this.addDay.hide();
                this.errorMsg.fadeOut();
            } else {
                this.addDay.show();
            }
        }
    }

    newDay(){
        this.flagNewDay = 'newDay';
        this._newDay = new Day(null, this.config.days_names);
        this.days_array[this.id].push(this._newDay);
        this._newDay.show();
        this.errorMsg.hide();
        this.addDay.hide();
        this.edit(this._newDay.getRef());
        console.log('new day: ',this.days_array);
    }

    cancelNew(control){
        this.days_array[this.id].pop();
        this.cancel(control);
        this._newDay.delete();
        console.log('cancel new');
    }

    hide(){
        $('#days-bundle').slideUp();
    }

    show(){
        $('#days-bundle').slideDown();
    }
}