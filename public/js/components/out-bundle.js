import IconOutputBundle from './icon-bundle-output.js';
import DaysBundle from './days-bundle.js';


export default class OutBundle {
    value;
    content = `
    <div class="out-bundle border_1" style='display:none;'>
        <div class="out-selector-bundle mb-3">
            <div class="out-label-selector-bundle d-flex justify-content-between">
                <label class="label-out-selector label" for="out">Salida</label>
            </div>
            <input type="hidden" id="output_id" name="output_id" value="">
            <select class="out-selector form-control" name="out" id="out"></select>
        </div>
    </div>
    `;
    iconEdit;
    eventEdit;
    output;
    constructor(config){
        let thisclass = this;
        this.config = config;
        $('#program-form').append(this.content);
        this.iconEdit = new IconOutputBundle();
        this.eventEdit = this.iconEdit.click();//guarda el evento click del boton edit.
        $('#out').change(()=>thisclass.setValue($('#out').val())); //actualiza select de outputs
        this.daysBundle = new DaysBundle(this.config);
        this.daysBundle.show();
        // this.eventEdit.click(()=>console.log(this.iconEdit.getState()));//agregar que hace cuando se presiona el boton edit.
        this.outs_names = this.config.getOutNames();
        this.firstTime = true;
    }

    update(outputs){
        let thisclass = this;
        let selected = '';
        let selector = $('#out');
        selector.html('');
        for (let index = 0; index < outputs.length; index++) {
            if(this.firstTime && index == 0){
                selected = 'selected';
                this.value = outputs[0].id;
            } else {
                selected = '';
            } 
            selector.append(`<option value="${outputs[index].id}" ${selected} >${outputs[index].output_name}</option>`);
        }
        selector.val(this.value);
        this.output = outputs.filter(output=>output.id == this.value)[0];
        this.daysBundle.update(this.output);
        if(this.config.isEmpty(this.output)){
            this.iconEdit.hide();
        } else {
            this.iconEdit.show();
        }  
        console.log(this.output);
        this.firstTime = false;
    }

    setValue(val){
        this.value = val;
        $('#output_id').val(this.value);
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