import IconProgramBundle from './icon-bundle-program.js';
import newProgramForm from './new-program-form.js';
import OutBundle from './out-bundle.js';

export default class ProgramBundle {
    value;
    content = `
    <div class="program-selector-bundle border_1" id="program-selector-bundle">
        <div id='program-cont' style='display:none;'>
            <div class="program-label-selector-bundle ">
                <label class="label-program-selector label" for="out">Programas</label>
            </div>
            <select class="program-selector form-control" name="program" id="program"></select>
            <div class='error' id="program-error"style='display:none;'></div>
        </div>
    </div>
    `;
    constructor(config){
        let thisclass = this;
        $('#program-form').append(this.content);
        $('#program').change(()=>thisclass.setValue($('#program').val()));//eventor de cambio en el selector 
        /*MENU DE ICONOS NEW, EDIT, ERASE PROGRAM */
        this.iconProgramBundle = new IconProgramBundle();  
        this.iconProgramBundle.newEvent().click(()=>thisclass.newProgram());
        this.iconProgramBundle.eraseEvent().click(()=>thisclass.eraseProgram()); 
        this.iconProgramBundle.editEvent().click(()=>thisclass.editProgram());
        /*MENU OUTPUT */
        this.outBundle = new OutBundle(config);
        this.outBundle.show();
    }
    endEditProgram(){
        console.log("jajaja");
    }
    update(programs, outputs){
        let thisclass = this;
        let selected;
        let firstTime = false;
        if(typeof this.value === "undefined"){
            firstTime = true;
        }
        let selector = $('#program');
        selector.html('');
        for (let index = 0; index < programs.length; index++) {
            if(firstTime){
                this.value = programs[0].id;
            } 
            if(this.value == programs[index].id){
                selected = 'selected';
            } else {
                selected = '';
            }
            selector.append(`<option value="${programs[index].id}" ${selected}>${programs[index].name}</option>`);
        }

        selector.val(thisclass.value); // actualiza el selector con el valor de Value.
        this.iconProgramBundle.update(programs);
        this.iconProgramBundle.show();
        this.outBundle.update(outputs);//acualiza select de salidas
    }
    newProgram(){
        this.hide();
        let formEdit = new newProgramForm();
        formEdit.show();
        this.iconProgramBundle.hide();
        this.outBundle.hide();
    }
    eraseProgram(){
        console.log('erase program')
    }
    editProgram(){
        console.log('edit program')
    }
    change(){
        return $('#program');
    }
    outSelector(){
        
        return this.outBundle.change();
    }

    hide(){
        $('#program-cont').slideUp();
    }

    show(){
        $('#program-cont').slideDown();
    }

    setValue(val){
        this.value = val;
    }
    getValue(){
        return this.value;
    }

    showError(errorText){
        $('#program-error').text(errorText);
        $('#program-error').fadeIn();//No hay programas creados aun
    }

}