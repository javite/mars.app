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
        this.days = [];
        this.id = 0;
    }

    update(output){
        this.empty();
        this.output = output;
        this.daysSubmit.hide();
        if(this.config.isEmpty(output)){
            this.errorMsg.fadeIn();
            this.addDay.show();
            this.id = -1;
            this.days = [];
            console.log("output vacio")
        } else {
            this.errorMsg.fadeOut();
            console.log(output.days.length);
            if(output.days.length > 0){
                for (let id = 0; id < output.days.length; id++) {
                    this.days[id] = new Day(id, output, this.days_names);
                    this.days[id].show();
                    console.log(output.days);
                    if(output.days[0] == 7){
                        this.addDay.hide();
                    } else {
                        this.addDay.show();
                    }
                    this.id = id;
                }
            }
        }
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