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
        let selected = '';
        this.selector = $('#out');
                
        $('#out').change(()=>{  //selector de salida
            this.id_selected = this.selector.val();
            console.log('out id selected: ', this.id_selected);
            this.update();
        })

        let _getOutputs = this.getOutputs(this.program.id)
        _getOutputs.then(json => {
            console.log('outputs', json);
            this.outputs = json.outputs;
            this.days = json.days;
            this.id_selected = this.outputs[0].id;
            for (let index = 0; index < this.outputs.length; index++) {
                if(this.firstTime && index == 0){
                    selected = 'selected';
                } else {
                    selected = '';
                } 
                this.selector.append(`<option value="${this.outputs[index].id}" ${selected} >${this.outputs[index].output_name}</option>`);
            }
            /*CONTENEDOR DIAS*/
            this.daysBundle = new DaysBundle(this.days,this.outputs[0].outputs_names_id, this.config);
            this.daysBundle.show();
            this.show();
        })
    }

    getOutputs(program_id){
        return fetch(`/getOutputs/${program_id}`)
        .then(data => data.json())
        .catch(error => console.error(error))
    }

    update(){
        this.output = this.outputs.filter(output=>output.id == this.id_selected)[0];
        this.daysBundle.update(this.output.outputs_names_id);
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

    remove(){
        $('.out-bundle').remove();
    }
    
    // newProgram(program_id){
    //     this.program_id = program_id;
    //     console.log(this.program_id);
    //     let _getOutputs = this.getOutputs(this.program.id)
    //     _getOutputs.then(json => {
    //         console.log('outputs', json);
    //         this.outputs = json.outputs;
    //         this.days = json.days;
    //         this.id_selected = this.outputs[0].id;
    //         this.daysBundle = new DaysBundle(this.days,this.outputs[0].outputs_names_id, this.config);
    //         this.daysBundle.show();
    //     })
    // }
}