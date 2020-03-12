import Day from './day.js';
import AddDayButton from './add-day-button.js';


export default class DaysBundle {
    

    constructor(config){
        this.content = "<div class='row days-bundle' id='days-bundle' style='display:none;'></div>";
        let thisclass = this;
        $('.out-bundle').append(this.content); 
        this.days_names = config.getDaysNames();
        this.config = config;
        $('.out-bundle').append("<div class='error' id='error-empty-output' style='display: none'>La salida no tiene programa aun.</div>");
        this.errorMsg =  $('#error-empty-output');
        this.addDay = new AddDayButton();
        this.addDayClick = this.addDay.click();
        this.addDayClick.click(()=>thisclass.newDay());
        this.days_array = [];
        this.id = 0;

    }

    update(days, output_name_id){
        this.empty();
        this.days = days;
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

    cancel(){
        this.days_array.pop();

    }

    success(){
        this.checkDOM();
    }
    
}