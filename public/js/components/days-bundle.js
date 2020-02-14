import Day from './day.js';
import AddDayButton from './add-day-button.js';
import DaysSubmit from './days-submit.js';

export default class DaysBundle {
    content = "<div class='row days-bundle' id='days-bundle' style='display:none;'></div>";

    constructor(config){
        let thisclass = this;
        $('.out-bundle').append(this.content); 
        this.days_names = config.getDaysNames();
        this.config = config;
        $('.out-bundle').append("<div class='error' id='error-empty-output' style='display: none'>La salida no tiene programa aun.</div>");
        this.errorMsg =  $('#error-empty-output');
        this.addDay = new AddDayButton();
        this.daysSubmit = new DaysSubmit();
        this.daysSubmit.submit().click(()=>this.submit());
        this.daysSubmit.cancel().click(()=>this.cancel());
        this.addDayClick = this.addDay.click();
        this.addDayClick.click(()=>thisclass.newDay());
        this.days_array = [];
        this.id = 0;
        this.flagNewDay = false;
    }

    update(days, output_name_id){
        this.empty();
        this.days = days;
        this.daysSubmit.hide();
        this.id = output_name_id - 1;
        this.days_array = []; 
        if(this.days[this.id].length == 0){
            this.checkDOM();
        } else {
            this.errorMsg.fadeOut();
            for (let index = 0; index < this.days[this.id].length; index++) {
                this.days_array[index] = new Day(this.days[this.id][index], this.config.days_names);
                this.days_array[index].show();
                console.log(this.days_array);
            }
            this.checkDOM();
        }
    }

    checkDOM(){
        if(this.days_array.length == 0){
            this.errorMsg.fadeIn();
            this.addDay.show();
        } else {
            if(this.days_array[0].day.day == 7){
                this.addDay.hide();
                this.errorMsg.hide();
            } else {
                this.addDay.show();
            }
        }

    }
    newDay(){
        let output = null;
        let newDay = new Day(null, this.config.days_names);
        this.days_array.push(newDay);
        console.log('new day: ',this.days_array);
        newDay.show();
        newDay.enable();
        this.errorMsg.hide();
        this.addDay.hide();
        this.daysSubmit.show();
        this.flagNewDay = true;
    }

    empty(){
        $('.day').remove();
    }
    hide(){
        $('#days-bundle').slideUp();
    }

    show(){
        $('#days-bundle').slideDown();
    }

    submit(){
        let func;
        let form = $('#program-form').serialize(); //.serializeArray()
        console.log(form);
        if(this.flagNewDay){
            func = 'newDay';
            this.flagNewDay = false;
        } else {
            func = 'updateDay';
        }
        $.post(func, form)
        .done((day_created)=>{
            let length = this.days_array.length;
            console.log(this.days_array);
            console.log('length:', length);
            console.log(this.days_array[length - 1]);//.update(day_created);
            this.days_array[length - 1].update(day_created, this.config.days_names);
            this.days_array[length - 1].disable();
            this.success();
        })
        .fail((err)=>console.log('Error al agregar dia: ',err))
    }
    cancel(){
        this.days_array.pop();
        this.daysSubmit.hide();
    }
    success(){
        this.checkDOM();
        this.daysSubmit.hide();
    }
    

}