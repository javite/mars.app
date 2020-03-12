import DaysBundle from './days-bundle.js';


export default class OutBundle {

    constructor(program, config){
        this.id_selected;
        this.content = `<div class="out-bundle border_1" style='display:none;'>
                            <div class="out-selector-bundle mb-3">
                                <div class="out-label-selector-bundle d-flex justify-content-between">
                                    <label class="label-out-selector label" for="out">Salida</label>
                                </div>
                                <select class="out-selector form-control" name="output_id" id="out"></select>
                            </div>
                        </div>`;
        this.iconEdit;
        this.eventEdit;
        this.output;
        this.days;
        this.program = program;
        this.firstTime = true;
        this.config = config;
        this.outs_names = this.config.getOutNames();
        $('#program-form').append(this.content);
        
        /*CONTENEDOR DIAS*/
        this.daysBundle = new DaysBundle(this.config);
        this.daysBundle.show();
        
         
        $('#out').change(()=>{  //selector de salida
            this.id_selected = $('#out').val();
            console.log('out id selected: ', this.id_selected);
            this.update();
        })

        this.getOutputs(this.program.id);

    }

    getOutputs(program_id){
        fetch(`/getOutputs/${program_id}`)
            .then(data => data.json())
            .then(json => {
                console.log('outputs', json);
                this.outputs = json.outputs;
                this.days = json.days;
                this.id_selected = this.outputs[0].id;
                this.update();
                this.show();
            })
            .catch(error => console.error(error))
    }

    newProgram(program_id){
        this.program_id = program_id;
        console.log(this.program_id);
        this.getOutputs(this.program_id);
    }

    update(){
        let selected = '';
        let selector = $('#out');
        selector.html('');
        for (let index = 0; index < this.outputs.length; index++) {
            if(this.firstTime && index == 0){
                selected = 'selected';
                // this.id_selected = this.outputs[0].id;
            } else {
                selected = '';
            } 
            selector.append(`<option value="${this.outputs[index].id}" ${selected} >${this.outputs[index].output_name}</option>`);
        }
        selector.val(this.id_selected);
        this.output = this.outputs.filter(output=>output.id == this.id_selected)[0];
        console.log(this.id_selected)
        this.daysBundle.update(this.days, this.output.outputs_names_id);
        if(this.config.isEmpty(this.days)){
            // this.iconEdit.hide();
        } else {
            // this.iconEdit.show();
        }  
        console.log('days:',this.days);
        this.firstTime = false;
        this.show();
    }

    setValue(val){
        this.id_selected = val;
    }
    
    change(){
        return $('#out');
    }

    hide(){
        $('.out-bundle').slideUp();
    }

    show(){
        $('.out-bundle').slideDown();
    }

}