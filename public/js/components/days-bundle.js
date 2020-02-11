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
        this.daysSubmit.submit().click(()=>thisclass.submit());
        this.daysSubmit.cancel().click(()=>thisclass.cancel());
        this.addDayClick = this.addDay.click();
        this.addDayClick.click(()=>thisclass.newDay());
        this.days_array = [];
        this.id = 0;
    }

    update(days, id){
        this.empty();
        this.days = days;
        this.daysSubmit.hide();
        this.id = id;
        console.log(this.id);
        if(this.days[this.id].length == 0){
            this.errorMsg.fadeIn();
            this.addDay.show();
            this.days = [];
            console.log("no hay dias")
        } else {
            this.errorMsg.fadeOut();
                for (let index = 0; index < this.days[this.id].length; index++) {
                    this.days_array[index] = new Day(this.days[this.id][index]);
                    this.days_array[index].show();
                    console.log(this.days);
                }
            }
                    // if(this.days[0] == 7){
                    //     this.addDay.hide();
                    // } else {
                    //     this.addDay.show();
                    // }
        //             this.id = id;
        //         }
        //     }
        // }
    }

    newDay(){
        let output = null;
        this.id += 1;
        let newDay = new Day(this.id, output, this.days_names);
        this.days.push(newDay);
        newDay.show();
        this.enableDays();
        this.errorMsg.hide();
        this.addDay.hide();
        this.daysSubmit.show();
    }

    enableDays(){
        for (let i = 0; i < this.days.length; i++) {
            this.days[i].enable();
        }
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
        let data = JSON.stringify($('#program-form').serialize()); //.serializeArray()
        console.log(data);
        if(this.days.length == 1){
            func = 'newOutput.php';
        } else {
            func = 'updateOutput.php';
        }
        // fetch(func, {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //       },
        //     body: data
        // })
        // .then(data => data.text())
        // .then(json =>  {
        //     // getOutputs(program_id, out_num); 
        //     console.log(json);
        // })
        // .catch(function(err) {
        //     // popUp(err);
        //     console.log("Update or add Output: ", err);
        // });
    }
    cancel(){
        this.days.pop();
        this.update(this.output);
        this.flag_new_day = false;
    }
    

}